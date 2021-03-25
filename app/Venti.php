<?php

namespace App;

class Venti
{
    public static function itnev($ps, $spans)
    {
        $patients = [];

        $countP = count($spans) - 1;
        $i = 0;
        while ($i < $countP) {
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
            // \Log::debug($i);
        }

        \Log::info($patients);
    }
}
