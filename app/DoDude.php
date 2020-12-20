<?php
namespace App;

use App\Models\Dude;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
                    'target' => 'dc',
                    'body' => 'failed',
                ]);
                echo 'key '.$id." failed\n";
                continue;
            }

            if (strpos($response->body(), '/body') < 13000) {
                Dude::create([
                    'key' => $id,
                    'target' => 'dc',
                    'body' => 'no data',
                ]);
                echo 'key ' . $id . " no data\n";
                continue;
            }

            Dude::create([
                'key' => $id,
                'target' => 'dc',
                'body' => 'ok',
            ]);
            Storage::put('public/' . intval($id / 10000) . '/dc_' . $id . '.html', $response->body());
        }
    }
}
