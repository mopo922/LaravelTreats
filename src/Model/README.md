# LaravelTreats\Model

Eloquent extensions for Laravel 5.

## Base Model

The base model provides bonus functionality that all of your ELoquent models can use.

### Many-to-many Relationship Auto-fills

This feature allows you to send in an array of related model IDs as part of your
normal input, and they will automatically be saved to the database pivot table.
Just add a `protected $autofillRelationships` property to your model that represents
an array of relationship method names, and make sure the same values are in your
`$fillable` array:

```php
class MyModel extends Eloquent {

    /** @var array The attributes that are mass assignable. */
    protected $fillable = ['name', 'users'];

    /** @var array $autofillRelationships List of many-to-many relationships that can be autofilled. */
    protected $autofillRelationships = ['users'];

    ...
```


## Traits

### LaravelTreats\Model\Traits\HasCompositPrimaryKey

This PHP trait can be used to adapt any Eloquent model to handle a composite PK.
Simply add the following `use` statement at the top of your model:

```php
class MyModel extends Eloquent {
    use \LaravelTreats\Model\Traits\HasCompositePrimaryKey;

    /**
     * The primary key of the table.
     *
     * @var array
     */
    protected $primaryKey = ['key1', 'key2'];

    ...
```
