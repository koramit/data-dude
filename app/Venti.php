<?php

namespace App;

use App\Models\VentiRecord;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Venti
{
    public static function itnev($patients)
    {
        $medicineCases = [];
        foreach ($patients as $patient) {
            // Sometime, there are more than one case for a HN at the same time
            // maybe Human error so, we have to use encounter date for
            // detecting a specific case because just HN and
            // not discharge yet is not enough
            $los = explode(':', $patient['los']);
            unset($patient['los']);
            $minutes = (((int) $los[0]) ?? 0) * 60;
            $minutes += (((int) $los[1]) ?? 0);
            $minutes = $minutes < 0 ? 0 : ($minutes * -1);
            $encounteredAt = now()->addMinutes($minutes);

            // remove fields
            unset($patient['bed']);
            unset($patient['dx']);

            // if no hn in DB or hn discharged then create new case
            $case = VentiRecord::where('no', 'like', $encounteredAt->format('ymdH').'%'.$patient['hn'])
                               ->whereNull('dismissed_at')
                               ->get();

            if ($case->count() > 1) {
                Log::critical('MULTIPLE CASES OF A HN AT THE SAMETIME!!!');
                continue;
            } elseif ($case->count() === 1) {
                $case = $case[0];
            } else {
                $case = null;
            }

            if (! $case) { // new case - create
                $patient += [
                    'no' => $encounteredAt->format('ymdHi').$patient['hn'],
                    'encountered_at' => $encounteredAt,
                ];
                if ($patient['medicine']) {
                    $patient += [
                        'tagged_med_at' => $encounteredAt,
                    ];
                }
                try {
                    $case = VentiRecord::create($patient);
                    $case->needSync = true;
                } catch (Exception $e) {
                    Log::error('create case error => '.$patient['no']);
                    continue;
                }
            } else { // old case - update
                $updates = false;
                foreach ($patient as $key => $value) {
                    if ($case->$key != $value) {
                        Log::info($patient);
                        $case->$key = $value;
                        $updates = true;
                        if ($key == 'medicine' && $value) {
                            Log::info('event case tagged med '.$case->no);
                            $case->tagged_med_at = now();
                        }
                    }
                }
                try {
                    if ($updates) {
                        $case->save();
                        $case->needSync = true;
                    }
                } catch (Exception $e) {
                    Log::error('update case error => '.$case->no);
                    continue;
                }
            }

            if ($case->medicine) {
                $medicineCases[] = $case;
            }
        }

        $list = collect($patients)->pluck('hn')->toArray();
        Cache::put('latestlist', $list);
        foreach ($medicineCases as $case) {
            if ($case->needSync) {
                Log::info('Need Sync : '.$case->no);
            }
        }

        $cases = VentiRecord::whereNull('dismissed_at')->whereNotIn('hn', $list)->get();

        foreach ($cases as $case) {
            $case->update(['dismissed_at' => now()]);
            Log::info('DC from er-queue '.$case->no);
        }

        static::monitor();
    }

    public static function future($patients)
    {
        return [];
        // $medicineCases = [];
        // foreach ($patients as $patient) {
        //     $case = VentiRecord::whereHn($patient['hn'])
        //                        ->whereNull('dismissed_at')
        //                        ->first();

        //     // add case to history if its not exists
        //     $history = Cache::get('venti-history', collect([]));
        //     if (! $case) {
        //         $dismissedAt = Carbon::parse($patient['dismissed_at'], 'asia/bangkok')->tz('utc');
        //         $old = VentiRecord::whereHn($patient['hn'])
        //                           ->whereDismissedAt($dismissedAt)
        //                           ->first();
        //         if ($old) { // case already exists and discharged
        //             continue;
        //         }

        //         $old = $history->firstWhere('hn', $patient['hn']);
        //         if (! $old) {
        //             $history->push($patient);
        //             Cache::put('venti-history', $history);
        //         }
        //         continue;
        //     }

        //     // case found - remove it from history cache
        //     $history = $history->filter(function ($record) use ($patient) {
        //         return $record['hn'] != $patient['hn'];
        //     });
        //     Cache::put('venti-history', $history);

        //     // update case
        //     $dirty = false;
        //     foreach (['movement', 'cc', 'dx', 'insurance', 'outcome'] as $field) {
        //         if ($patient[$field] && ($patient[$field] != '') && ($case->$field != $patient[$field])) {
        //             $case->$field = $patient[$field];
        //             $dirty = true;
        //         }
        //     }

        //     foreach (['encountered_at', 'dismissed_at'] as $field) {
        //         $timestamp = Carbon::parse($patient[$field], 'asia/bangkok')->tz('utc');
        //         if ((! $case->$field) || ($case->$field->format('Y-m-d H:i') != $timestamp->format('Y-m-d H:i'))) {
        //             $case->$field = $timestamp;
        //             $dirty = true;
        //             Log::info('Update timestamp form history');
        //         }
        //     }

        //     if ($dirty) {
        //         $case->save();
        //         Log::info('Need Sync : '.$case->no);
        //         if ($case->medicine) {
        //             $medicineCases[] = $case;
        //         }
        //     }
        // }

        // if (count($medicineCases)) {
        //     //update
        // }
    }

    public static function monitor()
    {
        $now = [
            'cases' => VentiRecord::count(),
            'dc' => VentiRecord::wherenotNull('dismissed_at')->count(),
            'med' => VentiRecord::whereMedicine(true)->count(),
            'venti' => count(Cache::get('latestlist', [])),
        ];
        $monitor = Cache::get('venti-monitor', []);

        $alertAt = (int) env('VENTI_ALERT');
        if (count($monitor) < $alertAt) {
            $monitor[] = $now;
            Cache::put('venti-monitor', $monitor);

            return;
        }

        if ($monitor[0]['cases'] != $now['cases'] ||
            $monitor[0]['dc'] != $now['dc'] ||
            $monitor[0]['med'] != $now['med'] ||
            $monitor[0]['venti'] != $now['venti'] ||
            count($monitor) > ($alertAt + 10)
        ) {
            Cache::put('venti-monitor', []);

            return;
        }

        $monitor[] = $now;
        Cache::put('venti-monitor', $monitor);
        Log::critical('venti not update for '.count($monitor).' iterations');
    }

    public static function rotateCase()
    {
        $case = VentiRecord::whereMedicine(true)
                           ->whereNull('dismissed_at')
                           ->orderBy('updated_at')
                           ->first();
        if (! $case) {
            return ['hn' => false];
        }

        $case->touch();

        return ['hn' => $case->hn, 'no' => $case->no];
    }

    public static function rotateHistory()
    {
        $case = VentiRecord::whereMedicine(true)
                           ->whereNotNull('dismissed_at')
                           ->whereNull('outcome')
                           ->orderBy('encountered_at')
                           ->first();

        if (! $case) {
            return ['hn' => false];
        }
        $pageStart = ((int) (now()->diffInHours($case->encountered_at) / 24) + 1) * 6;

        return [
            'hn' => $case->hn,
            'no' => $case->no,
            'pageStart' => $pageStart,
            'timestamp' => $case->encountered_at->tz('asia/bangkok')->format('Y-m-d H:i'),
        ];
    }

    public static function profile($profile)
    {
        $case = VentiRecord::whereNo($profile['no'])->whereHn($profile['hn'])->first();

        if (! $case) {
            return;
        }

        unset($profile['found']);
        unset($profile['encountered_at']);
        unset($profile['hn']);
        unset($profile['no']);

        $updates = false;
        foreach ($profile as $key => $value) {
            if ($case->$key != $value) {
                Log::info($profile);
                $case->$key = $value;
                $updates = true;
            }
        }

        if ($updates) {
            $case->save();
        }
    }
}
