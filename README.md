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
