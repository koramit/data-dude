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
                        $case->needSync = true;
                    }
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

    public static function future($patients)
    {
        // Log::debug('future');
        // Log::debug($patients);
        foreach ($patients as $patient) {
            $encounteredAt = Carbon::parse($patient['encountered_at'], 'asia/bangkok')->tz('utc');
            $no = $encounteredAt->format('ymdHi').$patient['hn'];
            $case = VentiRecord::whereNo($no)->first();
            if (! $case) {
                $history = Cache::get('venti-history', collect([]));
                $old = $history->firstWhere('hn', $patient['hn']);
                if (! $old) {
                    $history->push($patient);
                    Cache::put('venti-history', $history);
                }
                continue;
            }

            $dirty = false;
            foreach (['movement', 'cc', 'dx', 'insurence', 'outcome'] as $field) {
                if ($patient[$field] && $case->$field != $patient['field']) {
                    $case->$field = $patient['field'];
                    $dirty = true;
                }
            }

            foreach (['encountered_at', 'dismissed_at'] as $field) {
                $timestamp = Carbon::parse($patient['field'], 'asia/bangkok')->tz('utc');
                if ($case->$field->format('Y-m-d H:i') != $timestamp->format('Y-m-d H:i')) {
                    $case->$field = $timestamp;
                    $dirty = true;
                }
            }

            if ($dirty) {
                $case->save();
                Log::info('Need Sync : '.$case->no);
            }
        }
    }
}
