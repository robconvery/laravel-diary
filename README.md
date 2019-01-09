# laravel-diary
Laravel package used to scaffold a diary

## Installation

Add to `repositories` section of composer.json 
```$xslt
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/robconvery/laravel-diary"
    }
],
```
Add to `require` section of composer.json
```$xslt
"require": {
    ...
    "robconvery/laravel-diary": "^1.0"
},
```

To create the default file structure
```$xslt
artisan vendor:publish --tag=diary
``` 
To bespoke the routes add the following to `routes/web.php`. Adjust to suite.
```$xslt
Route::group([
    'middleware' => ['auth']
], function () {

    Route::get('/diary', 'DiaryController@diary')
        ->name('diary');

    Route::get('/diary/days', 'DiaryController@days')
        ->name('diary-days');

    Route::get('/diary/days/{date}', 'DiaryController@daysWithDate')
        ->name('diary-days-date');

    Route::get('/diary/entries/{date}', 'DiaryDataController@entries')
        ->name('diary-entries');
});
```
