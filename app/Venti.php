<?php

namespace App;

use App\Models\VentiRecord;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Venti
{
    public static function itnev($patients)
    {
        foreach ($patients as $patient) {
            // if no hn in DB or hn discharged then create new case
            $case = VentiRecord::whereHn($patient['hn'])
                               ->whereNull('dismissed_at')
                               ->first();
            $los = explode(':', $patient['los']);
            unset($patient['los']);
            if (! $case) {
                // create case
                $minutes = (((int) $los[0]) ?? 0) * 60;
                $minutes += (((int) $los[1]) ?? 0);
                $encounteredAt = now()->addMinutes($minutes * -1);
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
                } catch (Exception $e) {
                    Log::error('create case error');
                    Log::error($patient);
                }
                $case->needSync = true;
            } else {
                // update case
                $updates = false;
                foreach ($patient as $key => $value) {
                    if ($case->$key != $value) {
                        $case->$key = $value;
                        $updates = true;
                        if ($key == 'dx') {
                            Log::info('event dx change');
                        }
                        if ($key == 'medicine' && $value) {
                            Log::info('event case tagged med');
                            $case->tagged_med_at = now();
                        }
                    }
                }
                try {
                    if ($updates) {
                        $case->save();
                    }
                    $case->needSync = true;
                } catch (Exception $e) {
                    Log::error('update case error');
                    Log::error($patient);
                }
            }

            if ($case->medicine) {
                $medicineCases[] = $case;
            }
        }

        $latestlist = Cache::get('latestlist', []);
        $list = collect($patients)->pluck('hn')->toArray();
        $dismissedCases = [];
        collect($latestlist)->diff($list)->each(function ($hn) use ($dismissedCases) {
            $case = VentiRecord::whereHn($hn)->whereNull('dismissed_at')->first();
            if ($case) {
                $case->update(['dismissed_at' => now()]);
                $dismissedCases[] = $case;
            } // sometime case may lose if not fetch for too long
        });

        Cache::put('latestlist', $list);
        if (count($dismissedCases)) {
            Log::info(collect($dismissedCases)->pluck(['hn', 'dismissed_at']));
        }

        foreach ($medicineCases as $case) {
            if ($case->needSync) {
                Log::info('Need Sync : '.$case->no);
            }
        }
    }

    public static function itnev_old($ps, $spans)
    {
        $patients = [];

        $countTags = count($spans) - 1;
        $i = 0;
        while ($i < $countTags) {
            if (is_numeric($spans[$i]) || $spans[$i] == '-' || (strlen($spans[$i]) <= 6)) {
                $patients[] = [
                    'bed' => $spans[$i],
                    'hn' => str_replace('HN', '', $spans[$i + 2]),
                    'name' => $spans[$i + 1],
                ];
                $i += 4;
            } else {
                $patients[] = [
                    'bed' => null,
                    'hn' => str_replace('HN', '', $spans[$i + 1]),
                    'name' => $spans[$i],
                ];
                $i += 3;
            }
        }

        $spanPatientsCount = count($patients);
        // Log::debug('parse p');
        // Log::debug($patients);

        $countTags = count($ps) - 1;
        $i = 0;
        $p = 0;
        while ($i < $countTags) {
            if ($ps[$i] == 'M') {
                $patients[$p] += [
                    'medicine' => true,
                    'recheck' => $ps[$i + 1],
                    'dx' => str_replace("\n", '', $ps[$i + 2]),
                    'counter' => $ps[$i + 3],
                    'los' => $ps[$i + 4],
                    'remark' => str_replace("\n", '', $ps[$i + 5]),
                ];
                $i += 6;
            } else {
                $isMed = strtolower($ps[$i + 2]) == 'C4';
                $patients[$p] += [
                    'medicine' => $isMed,
                    'recheck' => $ps[$i],
                    'dx' => str_replace("\n", '', $ps[$i + 1]),
                    'counter' => $ps[$i + 2],
                    'los' => $ps[$i + 3],
                    'remark' => str_replace("\n", '', $ps[$i + 4]),
                ];
                $i += 5;
            }
            Log::debug($patients[$p]);
            $p++;
            // if ($p >= $spanPatientsCount) {
            //     break;
            // }
        }

        $medicineCases = [];
        foreach ($patients as $patient) {
            // if no hn in DB or hn discharged then create new case
            $case = VentiRecord::whereHn($patient['hn'])
                               ->whereNull('dismissed_at')
                               ->first();
            $los = explode(':', $patient['los']);
            unset($patient['los']);
            if (! $case) {
                // create case
                $minutes = (((int) $los[0]) ?? 0) * 60;
                $minutes += (((int) $los[1]) ?? 0);
                $encounteredAt = now()->addMinutes($minutes * -1);
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
                } catch (Exception $e) {
                    Log::error('create case error');
                    Log::error($patient);
                }
                $case->needSync = true;
            } else {
                // update case
                $updates = false;
                foreach ($patient as $key => $value) {
                    if ($case->$key != $value) {
                        $case->$key = $value;
                        $updates = true;
                        if ($key == 'dx') {
                            Log::info('event dx change');
                        }
                        if ($key == 'medicine' && $value) {
                            Log::info('event case tagged med');
                            $case->tagged_med_at = now();
                        }
                    }
                }
                try {
                    if ($updates) {
                        $case->save();
                    }
                    $case->needSync = true;
                } catch (Exception $e) {
                    Log::error('update case error');
                    Log::error($patient);
                }
            }

            if ($case->medicine) {
                $medicineCases[] = $case;
            }
        }

        $latestlist = Cache::get('latestlist', []);
        $list = collect($patients)->pluck('hn')->toArray();
        $dismissedCases = [];
        collect($latestlist)->diff($list)->each(function ($hn) use ($dismissedCases) {
            $case = VentiRecord::whereHn($hn)->whereNull('dismissed_at')->first();
            $case->update(['dismissed_at' => now()]);
            $dismissedCases[] = $case;
        });

        Cache::put('latestlist', $list);
        if (count($dismissedCases)) {
            Log::info(collect($dismissedCases)->pluck(['hn', 'dismissed_at']));
        }

        foreach ($medicineCases as $case) {
            if ($case->needSync) {
                Log::info('Need Sync : '.$case->no);
            }
        }
    }
}
