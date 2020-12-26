<?php

use App\Models\Dude;
use App\Models\DudeForm;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dude', function () {
    return view('dude');
});

Route::post('/call-dude/{id}', function ($id) {
    $response = Http::get(env('TARGET_URL').$id);
    // $response = Http::get('https://pantip.com/topic/40389455');
    if (! $response->ok()) {
        // Dude::create([
        //     'key' => $id,
        //     'target' => 'admit',
        //     'body' => 'failed',
        // ]);
        return 'failed';
    }

    $body = $response->body();
    if (strpos($body, "<body>\r\n\r\n\r\n</body>") !== false) {
        return 'no data';
    }

    $begin = strpos($body, '<body>') + 6;

    $body = substr($body, $begin, strpos($body, '</body>') - 1 - $begin);

    return iconv('cp874', 'utf-8//IGNORE', $body);
});

Route::post('/dudes/{form}/{key}', function ($form, $key) {
    DudeForm::create(['key' => $key, 'form' => $form, 'content' => Request::all()]);
    // Log::info(Request::all());
    return 'ok';
});
