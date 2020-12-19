<?php

use App\Models\Dude;
use Illuminate\Support\Facades\Http;

class DoDude
{
    public function dude($id, $offset)
    {
        $stopAt = $id + $offset;
        for ($id; $id <= $stopAt; $id++) {
            $response = Http::get(env('TARGET_URL').$id);
            if (! $response->ok()) {
                Dude::create([
                    'key' => $id,
                    'target' => env('TARGET_URL'),
                    'body' => 'failed',
                ]);
                echo 'key '.$id." failed\n";
                continue;
            }
            Dude::create([
                'key' => $id,
                'target' => env('TARGET_URL'),
                'body' => $response->body(),
            ]);
            echo 'key '.$id." success\n";
        }
    }
}
