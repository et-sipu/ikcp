<?php

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

Route::get('sessionStatus', static function () {
    return ['user' => auth()->user() && auth()->user()->active ? true : null];
})->name('session_status');

Route::get('forms/crf', static function () {
    $pdf = App::make('dompdf.wrapper');
    $crf = new \App\Models\CRForm();
    $crf = $crf->create();
    $pdf->loadView('templates.forms.cash_requisition', compact('crf'));

    return $pdf->stream();
});

Route::get('forms/carf', static function () {
    $pdf = App::make('dompdf.wrapper');
    $carf = new \App\Models\CARForm();
    $carf = $carf->create();
    $pdf->loadView('templates.forms.cash_advance_requisition', compact('carf'));

    return $pdf->stream();
});

Route::get('forms/prf', static function () {
    $pdf = App::make('dompdf.wrapper');
    $prf = new \App\Models\PRForm();
    $prf = $prf->create();
    $pdf->loadView('templates.forms.payment_requisition', compact('prf'));

    return $pdf->stream();
});