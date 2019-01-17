<?php

Route::get('/diary', 'App\Http\Controllers\DiaryController@diary')
    ->name('diary');

Route::get('/diary/days', 'App\Http\Controllers\DiaryController@days')
    ->name('diary-days');

Route::get('/diary/days/{date}', 'App\Http\Controllers\DiaryController@daysWithDate')
    ->name('diary-days-date');

Route::get('/diary/entries/{date}', 'App\Http\Controllers\DiaryDataController@entries')
    ->name('diary-entries');

Route::post('/diary/{id}/update', 'App\Http\Controllers\DiaryDataController@store')
    ->name('diary-update');

// year/month
