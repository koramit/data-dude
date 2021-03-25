<?php

namespace App;

class Venti
{
    public static function itnev($ps, $spans)
    {
        $patients = [];

        $countP = count($ps);
        $i = 0;
        while ($i < $countP) {
            $patients[] = [
                'bed' => $ps[$i],
                'hn' => str_replace('HN', '', $ps[$i + 2]),
                'name' => $ps[$i + 1],
            ];
            $i += 4;
        }

        \Log::info($patients);
    }
}
