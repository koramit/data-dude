<?php

use App\Models\Dude;
use App\Models\DudeForm;
use Illuminate\Support\Facades\Http;
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
    if (request()->header('foobar', null) !== env('ITNEV_TOKEN')) {
        abort(404);
    }
    App\Venti::itnev(request()->patients);

    return ['foo' => 'bar'];
});

Route::post('/dudes/venti/history', function () {
    if (request()->header('foobar', null) !== env('ITNEV_TOKEN')) {
        abort(404);
    }
    App\Venti::future(request()->patients);

    return ['backto' => 'future'];
});

Route::post('/dudes/venti/hn', function () {
    return App\Venti::rotateCase();
});

Route::post('/dudes/venti/hn/history', function () {
    return App\Venti::rotateHistory();
});

Route::post('/dudes/venti/profile', function () {
    if (request()->header('foobar', null) !== env('ITNEV_TOKEN')) {
        abort(404);
    }
    App\Venti::profile(request()->profile);

    return ['profile' => 'poplife'];
});

Route::post('/dudes/{form}/{key}', function ($form, $key) {
    DudeForm::create(['key' => $key, 'form' => $form, 'content' => Request::all()]);

    return 'ok';
});

Route::get('/venti/{id}', function ($id) {
    return App\Models\VentiRecord::find($id);
});

Route::get('/bb-whiteboard', function () {
    return view('bb-whiteboard');
});

Route::get('/bb-profile', function () {
    return view('bb-profile');
});

Route::get('/bb-history', function () {
    return view('bb-history');
});

Route::get('/checkup/{ref}', function ($ref) {
    $ref = App\Models\VentiRecord::find($ref);

    $lastest = App\Models\VentiRecord::orderBy('created_at', 'desc')->first();

    $count = App\Models\VentiRecord::count();

    $dcCount = App\Models\VentiRecord::wherenotNull('dismissed_at')->count();

    return [
        'checkup' => now()->tz('Asia/bangkok')->format('d M Y H:i'),
        'session_last' => $ref->created_at->diffInMinutes($lastest->created_at).' mins',
        'cases' => $count,
        'dc' => $dcCount,
        'latest_at' => $lastest->created_at->longRelativeToNowDiffForHumans(),
        'venti' => count(\Cache::get('latestlist', [])),
    ];
});

Route::get('/weakup-theptai', function () {
    $data = ['api_app' => 'foo', 'api_token' => 'bar', 'endpoint' => env('CHECKUP_URL'), 'data' => ['foo' => 'bar']];

    return \Http::post(env('THEPTAI_URL'), ['payload' => json_encode($data, true)])->json();
});
