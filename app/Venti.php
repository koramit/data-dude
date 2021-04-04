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
                        'need_sync' => true, // sync med case only
                    ];
                }
                try {
                    $case = VentiRecord::create($patient);
                } catch (Exception $e) {
                    Log::error('create case error => '.$patient['no']);
                    continue;
                }
            } else { // old case - update
                $updates = false;
                foreach ($patient as $key => $value) {
                    if ($case->$key != $value) {
                        $case->$key = $value;
                        $updates = true;
                        if ($key == 'medicine' && $value) {
                            $case->tagged_med_at = now();
                        }
                    }
                }
                try {
                    if ($updates) {
                        $case->need_sync = true; // tag sync to all updated cases
                        $case->save();
                    }
                } catch (Exception $e) {
                    Log::error('update case error => '.$case->no);
                    continue;
                }
            }
        }

        // dismiss cases thoses removed from whiteboard
        $list = collect($patients)->pluck('hn')->toArray();
        Cache::put('latestlist', $list);
        VentiRecord::whereNull('dismissed_at')
                   ->whereNotIn('hn', $list)
                   ->get()
                   ->each(function ($case) {
                       $case->dismissed_at = now();
                       if ($case->medicine) {
                           $case->need_sync = true;
                       }
                       $case->save();
                   });

        // TODO sync data
    }

    public static function future($patients)
    {
        return [];
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

            return 'ok';
        }

        if ($monitor[0]['cases'] != $now['cases'] ||
            $monitor[0]['dc'] != $now['dc'] ||
            $monitor[0]['med'] != $now['med'] ||
            $monitor[0]['venti'] != $now['venti'] ||
            count($monitor) > ($alertAt + 10)
        ) {
            Cache::put('venti-monitor', []);

            return 'ok';
        }

        $monitor[] = $now;
        Cache::put('venti-monitor', $monitor);
        Log::critical('venti not update for '.count($monitor).' iterations');

        return 'need attention';
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

        $lastRotate = Cache::get('vent-last-history-search', '');
        if ($case->no != $lastRotate) {
            Cache::put('vent-last-history-search', $case->no);
            $pageStart = ((int) (now()->diffInHours($case->encountered_at) / 24) + 1) * 6;

            return [
                'hn' => $case->hn,
                'no' => $case->no,
                'pageStart' => $pageStart,
                'timestamp' => $case->encountered_at->tz('asia/bangkok')->format('Y-m-d H:i'),
            ];
        }

        $case->update(['outcome' => 'case removed']);

        return ['hn' => false];
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

        if (isset($profile['dismissed_at'])) {
            $case->dismissed_at = Carbon::parse($profile['dismissed_at'], 'asia/bangkok');
            $updates = true;
            unset($profile['dismissed_at']);
        }

        foreach ($profile as $key => $value) {
            if ($case->$key != $value) {
                $case->$key = $value;
                $updates = true;
            }
        }

        if ($updates) {
            $case->save();
            // TODO sync
        }
    }
}
