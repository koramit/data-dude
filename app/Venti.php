<?php

namespace App;

use App\Models\VentiRecord;

class Venti
{
    public static function itnev($ps, $spans)
    {
        $patients = [];

        $countTags = count($spans) - 1;
        $i = 0;
        while ($i < $countTags) {
            if (is_numeric($spans[$i])) {
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
                $patients[$p] += [
                    'medicine' => false,
                    'recheck' => $ps[$i],
                    'dx' => str_replace("\n", '', $ps[$i + 1]),
                    'counter' => $ps[$i + 2],
                    'los' => $ps[$i + 3],
                    'remark' => str_replace("\n", '', $ps[$i + 4]),
                ];
                $i += 5;
            }
            $p++;
        }

        foreach ($patients as $patient) {
            // if no hn in DB or hn discharged then create new case
            $case = VentiRecord::whereHn($patient['hn'])
                               ->whereNull('dismissed_at')
                               ->first();
            $los = explode(':', $patient['los']);
            unset($patient['los']);
            if (! $case) {
                // create case
                $case = VentiRecord::create($patient);
                $minutes = (((int) $los[0]) ?? 0) * 60;
                $minutes += (((int) $los[1]) ?? 0);
                $case->encountered_at = now()->addMinutes($minutes);
                $case->save();
            } else {
                // else update case
                $case->update($patient);
            }
        }

        // \Log::info($patients);
        // $lastlist = collect($patients)->pluck('hn')->toArray();
        // if (\Cache::has('lastlist')) {
        //     \Log::info();
        // } else {
        //     \Cache::put('lastlist', $lastlist);
        // }
    }
}
