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

If you'd simply like a "blank" HTML template with the `<head>` section already
filled out but no navigation or footer, extend the `web` layout instead and put
your page content in an `html-body` section:

```php
@extends('LaraveltTreats::layout.web')

@section('html-body')
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

### Site Name/Title, Description, and Domain

Open `resources/lang/vendor/laravel-treats/en/layout.php` to define your site's
name (appears in places like the HTML &lt;title&gt; tag and on the Terms and Privacy pages),
description (content for the &lt;meta name="description"&gt; tag), and domain
(used primarily in the Terms and Privacy pages).

```php
'site' => [
    'name' => 'My Site',
    'description' => 'A cool site designed by me.',
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

### Navigation

The default layout includes a customizable navigation menu. You can define your
navigation links in one of two ways. First, use the `nav.links` array in the
language file at `resources/lang/vendor/laravel-treats/en/layout.php` to set up
a default list of links:

```php
return [
    ...

    // Navigation info
    'nav' => [
        'dropdown-title' => 'My Account',
        'links' => [...],
    ],
```

Notice that there is also a `dropdown-title` option here. This is the only place
where you can set the label for your nav dropdown.

Once you've define your default navigation links in the language file, you can
dynamically override the defaults by setting a `navLinks` variable on the view
object from your controller:

```php
class MyController extends \LaravelTreats\Controller\Controller
{

    /** @return mixed General setup for the whole controller. */
    protected function general()
    {
        $this->layout->navLinks = [...];
```

Whichever way you choose to define your nav links, it should be an associative
array where the link is the key and the label (the text visible to the user)
is the value:

```php
$this->layout->navLinks = [
    '/user' => 'Edit Profile',
    '/user/password' => 'Change Password',
];
```

You can separate your links with dividers by simply nesting the groups in an
indexed array:

```php
$this->layout->navLinks = [
    [
        '/user' => 'Edit Profile',
        '/user/password' => 'Change Password',
    ],
    [
        '/logout' => 'Logout',
    ],
];
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

Next, add a `$modules` array property to the corresponding controller with the
list of links for your subnav. These values represent the last part of the URI
for each link, and the view automatically capitalizes the first letter of each word
for display.

```php
class MyController extends \LaravelTreats\Controller\Controller
{
    /** @var array $modules The general modules available on the site. */
    protected $modules = [
        'home',
        'about',
        'contact',
    ];
```

If you'd like to customize this behavior, you can pass the display text or HTML
as the key, and the link as the value:

```php
    protected $modules = [
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

## Custom Blade Directives

## @glyphicon

You can include a quick [glyphicon](http://getbootstrap.com/components/#glyphicons) with the `@glyphicon` directive:

```php
@glyphicon('plus-sign')
```
