# LaravelTreats

A collection of goodies for Laravel 5.

## Installation

Add this to your project's composer.json file:

```javascript
    // ...
    "require": {
        // ...
        "mopo922/laravel-treats": "^1.0",
        // ...
    },
    // ...
```

Then run `composer update`. That's it!

## Components

* [Controllers](src/Controller)
* [Eloquent Models](src/Model)
* [View Template](README_views.md)

## Service Provider

The LaravelTreatsServiceProvider is not required for all LaravelTreats features,
but does provide an interface for some benefits like the [ready-made view layout](README_views.md).

Simply add the LaravelTreatsServiceProvider to the `providers` array in `config/app.php`:

```php
'providers' => [
    // Other Service Providers

    LaravelTreats\LaravelTreatsServiceProvider::class,
],
```

 If you plan to take advantage of the default view layout, publish the necessary
 files to your `app` and `resources` directories by running the following command
 from your project's root directory:

 ```bash
 php artisan vendor:publish
 ```
