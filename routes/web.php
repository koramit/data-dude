<?php

use App\Models\DudeForm;
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

Route::post('/dudes/{form}/{key}', function ($form, $key) {
    DudeForm::create(['key' => $key, 'form' => $form, 'content' => Request::all()]);
    // Log::info(Request::all());
    return 'ok';
});
