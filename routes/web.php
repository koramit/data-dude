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

Route::post('/call-dude/{form}/{id}', function ($form, $id) {
    $response = Http::get(env('TARGET_URL_'.$form).$id);

    if (! $response->ok()) {
        Dude::create([
            'key' => $id,
            'form' => $form,
            'status' => 'failed',
        ]);

        return '';
    }

    $body = $response->body();
    $noData = false;
    if ($form == 'admit') {
        if (strpos($body, "<body>\r\n\r\n\r\n</body>") !== false) {
            $noData = true;
        }
    } else {
        if (strpos($body, '/body') < 13000) {
            $noData = true;
        }
    }

    Dude::create([
        'key' => $id,
        'form' => $form,
        'status' => $noData ? 'no data' : 'ok',
    ]);

    if ($noData) {
        return '';
    }

    $begin = strpos($body, '<body>') + 6;

    $body = substr($body, $begin, strpos($body, '</body>') - 1 - $begin);

    return iconv('cp874', 'utf-8//IGNORE', $body);
});

Route::post('/dudes/venti', function () {
    // \Log::info(request()->all());

    App\Venti::itnev(request()->p_tags, request()->span_tags);

    return ['foo' => 'bar'];
});

Route::post('/dudes/{form}/{key}', function ($form, $key) {
    DudeForm::create(['key' => $key, 'form' => $form, 'content' => Request::all()]);
    // Log::info(Request::all());
    return 'ok';
});

Route::get('/venti/{id}', function ($id) {
    return App\Models\VentiRecord::find($id);
});
