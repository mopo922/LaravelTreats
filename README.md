# LaravelTrafficControllers
A set of multi-purpose base controllers for Laravel 5.

## Installation
Add this to your project's composer.json file:

```javascript
    // ...
    "require": {
        // ...
        "mopo922/laravel-traffic-controllers": "dev-master",
        // ...
    },
    // ...
```

Then run `composer update`. That's it!

## Basic Usage

Composer takes care of autoloading, so you can simply have your controllers extend
the ones included in this package:

```php
<?php
namespace App\Http\Controllers;

class MyController extends \LaravelTraffic\Controller
{
    // ...
```

If your controllers don't use the default namespace of `App\Http\Controller`,
specify that in a `$strControllerNamespace` property on each of your controllers
(or a parent, which extends `LaravelTraffic\Controller` and is extended by all
of your controllers):

```php
class MyController extends \LaravelTraffic\Controller
{

    /** @var string $strControllerNamespace The default controller namespace. */
    protected $strControllerNamespace = 'App\Http\Controllers\\';
```

## Primary Controller

The top-level `LaravelTraffic\Controller` provides some useful boilerplate features
commonly used in web apps.

### General Controller Setup

If you have general setup actions you'd like to perform for all actions in your
controller, you can do so by adding a `general()` method to your controller:

```php
class MyController extends \LaravelTraffic\Controller
{

    /** @return mixed General setup for the whole controller. */
    protected function general()
    {
        // do stuff here
    }
```

If your `general()` method returns anything, it will override the default behavior,
which is to return (and eventually render) an `Illuminate\View\View` object.

### Default Views

`LaravelTraffic\Controller` will cause your app to automatically look for view
scripts in a directory structure that matches the routing path. For example,
if a user navigates to the `getIndex` action in a `HomeController`, `LaravelTraffic\Controller`
will look for a view script at `resources/views/home/index.blade.php` (or just `index.php`).

You can override the default mapping by adding a `$strViewScript` property with
the desired view script path:

```php
class MyController extends \LaravelTraffic\Controller
{

    /** @var string $strViewScript Allows child classes to override the standard view script mapping. */
    protected $strViewScript = 'custom.index';
```

If you need to override the standard view script path mapping in a conditional
manner, you can do so at the beginning of a custom `callAction()`:

```php
class MyController extends \LaravelTraffic\Controller
{

    /**
     * Extends parent::callAction()
     *
     * @param string $strMethod
     * @param array $aParameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function callAction($strMethod, $aParameters)
    {
        if ('index' === $strMethod)
            $this->strViewScript = 'custom.index';

        return parent::callAction($strMethod, $aParameters);
    }
```

To exclude certain actions within the controller from standard view setup, simply
add them to an `$aViewless` property on the controller:

```php
class MyController extends \LaravelTraffic\Controller
{

    /** @var array $aViewless Actions with no view script. */
    protected $aViewless = ['logout'];
```

## API Controller

The `LaravelTraffic\Api\Controller` provides a foundation for a RESTful API interface
that can be used to handle all of your app's CRUD opperations. It is an extension
of a Laravel "resource" controller.

### Basic usage

First, create a controller that extends `LaravelTraffic\Api\Controller`:

```php
<?php
namespace App\Http\Controllers\Api;

class GroupController extends \LaravelTraffic\Api\Controller
{
}
```

Then just add a route to `routes/web.php`:

```php
Route::group(['namespace' => 'Api', 'prefix' => 'api', 'as' => 'api.'], function() {
    Route::resource('group', 'GroupController');
}
```

If you choose to use a different URL route other than `/api/controllerName`, you'll
need to specify that using the `$strRoutePrefix` property on the controller:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @var string $strRoutePrefix The route name prefix used for the API. */
    protected $strRoutePrefix = 'resource.';
```

You now have a fully-functional REST enpoint at `example.com/api/group`!

### Database Interaction

`LaravelTraffic\Api\Controller` works on the assumption that you have an Eloquent
model whose name matches the controller name. So in the `GroupController` example
above, an `App\Model\Group` class will be used to perform the CRUD operations.
You can specify a custom model namespace using a `$strModelNamespace` property.

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @var string $strModelNamespace The default model namespace. */
    protected $strModelNamespace = '\\App\\';
```

To specify a custom model name, use a `$strModel` property on the API controller:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @var string $strModel The fully-qualified model class name. */
    protected $strModel = '\\App\\CustomGroup';
```

### Injecting the ID of the Current Logged-in User

If you'd like `LaravelTraffic\Api\Controller` to automatically inject the ID of
the current logged-in User, simply add a $bInjectUserId property to the controller:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @var bool $bInjectUserId Should we inject a user_id into the input for every request? */
    protected $bInjectUserId = true;
```

### Custom Redirect URL

By default, `LaravelTraffic\Api\Controller` will redirect back to the referring
page after the API action is performed. To specify a custom redirect URL for an
API controller, use the `$strRedirect` property:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @var string $strRedirect Optional custom redirect URL. */
    protected $strRedirect = '/home';
```

### Custom Lookup Logic

You can customize the way the API controller looks up records for the `index()`
action by adding a `findModel()` method:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /**
     * Use the current input to find a record.
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function findModel()
    {
        // do stuff

        return Illuminate\Database\Eloquent\Model;
    }
```

### Customize Filling the Model with the Input

You can customize the way the API controller fills a model with input during
create by adding a fillModel() method. Remember, objects in PHP are automatically
passed by reference.

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /**
     * Fill the model with the input.
     *
     * @param Illuminate\Database\Eloquent\Model $oModel
     */
    protected function fillModel(Model $oModel)
    {
        $oModel->fill($this->aInput);
    }
```

### Custom Fetch Validation Rules

You can customize the way the API controller looks up validation rules by adding
a  `getValidationRules()` method:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /**
     * Get validation rules for the current model.
     *
     * @return array
     */
    protected function getValidationRules()
    {
        $strClass = $this->strModel;

        return $strClass::$rules;
    }
```

### Custom Permission Checking

To add permission checking for the `update()` method, add a `canEdit()` method
to your controller. Returning false will block the request:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @return bool Check if the current User can edit the given model. */
    protected function canEdit(Model $oModel)
    {
        return true;
    }
```

To add general permission checking for the whole controller, add a `checkPermissions()`
method to the controller. Returning false will block the request:

```php
class GroupController extends \LaravelTraffic\Api\Controller
{

    /** @return bool Authorize the current User for this action. */
    protected function checkPermissions()
    {
        return true;
    }
```















