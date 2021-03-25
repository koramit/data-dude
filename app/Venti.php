<?php

namespace App;

class Venti
{
    public static function itnev($ps, $spans)
    {
        $patients = [];

        $countP = count($span);
        $i = 0;
        while ($i < $countP) {
            $patients[] = [
                'bed' => $span[$i],
                'hn' => str_replace('HN', '', $span[$i + 2]),
                'name' => $span[$i + 1],
            ];
            $i += 4;
        }

        \Log::info($patients);
    }
}
