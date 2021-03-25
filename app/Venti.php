<?php

namespace App;

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
                    'dx' => $ps[$i + 2],
                    'counter' => $ps[$i + 3],
                    'los' => $ps[$i + 4],
                    'remark' => $ps[$i + 5],
                ];
                $i += 6;
            } else {
                $patients[$p] += [
                    'medicine' => false,
                    'recheck' => $ps[$i],
                    'dx' => $ps[$i + 1],
                    'counter' => $ps[$i + 2],
                    'los' => $ps[$i + 3],
                    'remark' => $ps[$i + 4],
                ];
                $i += 5;
            }
            $p++;
        }

        // \Log::info($patients);
        $lastlist = collect($patients)->pluck('hn')->toArray();
        if (\Cache::has('lastlist')) {
            \Log::info();
        } else {
            \Cache::put('lastlist', $lastlist);
        }
    }
}
