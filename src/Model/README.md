# LaravelTreats\Model

Eloquent extensions for Laravel 5.

## LaravelTreats\Model\Traits\HasCompositPrimaryKey

This PHP trait can be used to adapt any Eloquent model to handle a composite PK.
Simply add the following `use` statement at the top of your model:

```php
class MyModel extends Eloquent {
    use \LaravelTreats\Model\Traits\HasCompositePrimaryKey;

    /**
     * The primary key of the table.
     *
     * @var string
     */
    protected $primaryKey = ['key1', 'key2'];

    ...
```
