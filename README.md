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
Run `composer update`

Create the default file structure
```$xslt
artisan vendor:publish --tag=diary
```
This process will also create the test `tests\Feature\Diary\DiaryDataTest.php`

Identify any alterations by running the automated test.

```$xslt
phpunit --group get_diary_test_data
```


## Setup
 
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
Create a class to act as the `DiaryEntryInterface`.

```$xslt
// Example class
class Diary implements Robconvery\Laraveldiary\DiaryEntryInterface
{
    ...
}
```

Create a `diary` service provider within the application.
```$xslt
artisan make:provider DiaryServiceProvider
```
Reference this provider within `config\app.php`

```$xslt
'providers' => [
    /*
     * Application Service Providers...
     */
    \App\Providers\DiaryServiceProvider::class
]
``` 


Add the following code to the service provider you created.
```$xslt
App()->bind(DiaryEntryInterface::class, function($app, $params) {
    // change class as required
    return new Diary();
});
``` 
 
