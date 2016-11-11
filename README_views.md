# View Template

LaravelTreats provides a default view layout that allows for instant creation of
a Laravel-based website. It also provides templates (DO NOT USE THESE IN PRODUCTION)
for Privacy Policy and Terms of Service pages. You'll need to activate the
[LaravelTreatsServiceProvider](../) in order to take advantage of this feature.

## Basic Usage

To take advantage of the default view layout, simply extend it in all of your
Blade view scripts, and define your page content in a `content` section:

```php
@extends('LaraveltTreats::layout.master')

@section('content')
    Hello World!
@endsection
```

### Styling

A default set of CSS rules is included in LESS form. You'll need to compile the LESS
rules into CSS using a command line tool or Laravel's [Elixir](https://laravel.com/docs/master/elixir).
Put the resulting CSS file at `public/css/styles.css`.

## Configuration

Once you've run `php artisan vendor:publish` you should have a new config file at
`config/laravel-treats.php` and a directory of translation files at
`resources/lang/vendor/laravel-treats/`.

### Site Name/Title and Domain

Open `resources/lang/vendor/laravel-treats/en/layout.php` to define your site's
name (appears in places like the HTML &lt;title&gt; tag and on the Terms and Privacy pages)
and domain (used primarily in the Terms and Privacy pages).

```php
'site' => [
    'name' => 'My Site',
    'domain' => env('APP_DOMAIN', 'example.com'),
],
```

Notice that if you've already defined your site's domain in your `.env` file,
you won't need to duplicate it here.

You can also change the display name of the terms & privacy links in the translation file:

```php
'link' => [
    'privacy' => 'Privacy Policy',
    'terms' => 'Terms of Use',
],
```

### Google Analytics

The default view layout includes Google Analytics tracking that activates automatically
if you provide your Tracking Code (like "UA-XXXXXXXX-X") in a `TREATS_GA_ID` variable
in your `.env` file, or directly in the PHP array in `config/laravel-treats.php`.

## Template Images

The template will automatically insert certain images for you if you put them in
the right locations:

* `public/img/jumbotron.jpg` - A "jumbotron" style background image for your public
(unauthenticated) landing page.
* `public/img/logo.jpg` - A logo to display in the header.

## Subnav

If you're also using the `LaravelTreats\Controller\Controller`, you can add a subnav
bar just below the header in two easy steps. First, add a subnav section to your
Blade view script:

```php
@section('subnav', '/')
```

The second parameter defines the prefix to all subnav links.

Next, add a `$aModules` array property to the corresponding controller with the
list of links for your subnav. These values represent the last part of the URI
for each link, and the view automatically capitalizes the first letter of each word
for display.

```php
class MyController extends \LaravelTreats\Controller\Controller
{
    /** @var array $aModules The general modules available on the site. */
    protected $aModules = [
        'home',
        'about',
        'contact',
    ];
```

If you'd like to customize this behavior, you can pass the display text or HTML
as the key, and the link as the value:

```php
    protected $aModules = [
        '<span class="glyphicon glyphicon-home"></span>' => 'home',
        'About Us' => 'about',
        'contact',
    ];
```

## Global Success & Error Messages

If a `success` or `error` element exists in the Laravel session, it will automatically
be displayed with the appropriate styling at the top of the page, just under the header.

## Append Hidden HTML

If you have hidden items to add to the bottom of the HTML layout, such as `<script>`
tags or a modal template, you can add those to your Blade view script in a
`body-append` section:

```php
@section('body-append')
    <div class="modal hide">...</div>
    <div class="modal-backdrop hide"></div>
@endsection
```
