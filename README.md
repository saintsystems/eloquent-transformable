# Eloquent Transformable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/saintsystems/eloquent-transformable.svg?style=flat-square)](https://packagist.org/packages/saintsystems/eloquent-transformable)
[![Total Downloads](https://img.shields.io/packagist/dt/saintsystems/eloquent-transformable.svg?style=flat-square)](https://packagist.org/packages/saintsystems/eloquent-transformable)
[![Build Status](https://travis-ci.org/saintsystems/eloquent-transformable.svg?branch=master)](https://travis-ci.org/saintsystems/eloquent-transformable)

Work with your Laravel Eloquent models the way you want them to look (not as they are) using a simple transformation layer.

## Installation

You can install the package in to any Laravel `5.8.*` via composer:

```bash
composer require saintsystems/eloquent-transformable
```

## Use Case

Laravel Eloquent is built on conventions. These conventions make certain assumptions like your primary keys being named `id` or the way foreign key columns should be named. You can override these conventions, but that requires a lot of configuration. Additionally, tools like [Laravel Nova](https://nova.laravel.com) assume the default Eloquent conventions. Configuring Nova and Eloquent to use difference naming conventions then becomes a pain and your code becomes brittle because it is tied explicitly to your unconventional database naming standards.

Databases aren't always under our control. They may be managed by DBAs, could be third-party systems, or there may simply be a legacy database that doesn't adhere to Laravel's conventions that we don't want to change or can't change to make it conform to Laravel's conventions. This could be in the form of unconventional table names, column prefixes, column naming conventions, foreign key names, etc. We don't always control the database over which a Laravel app might sit.

Eloquent Transformable allows you to define how you would like the database columns to look with a simple transformation and then use your Eloquent Models as if they did adhere to Eloquent's naming conventions.

Transformable can also be used as a simple transformation layer to shield your application from the underlying database structure.

## Usage

1. Create a base `Model.php` class in your project and add the `Transformable` trait to it.
```php
    namespace App;

    use Illuminate\Database\Eloquent\Model as EloquentModel;
    use SaintSystems\Eloquent\Transformable\Transformable;

    class Model extends EloquentModel
    {
        use Transformable;
    }
```

2. Create a Model that represents your "Actual" database model.

Assuming a table definition of:

**Table Name:** `tbl_Database_Table`

| Column           | Type   |
|------------------|--------|
|PK_Database_I     | int    |
|DB_Name           | varchar|
|FK_Foreign_Key_ID | int    |

```php
    namespace App;

    class ActualDatabaseModel extends Model
    {
        protected $table = 'tbl_Database_Table';

        protected $primaryKey = 'PK_Database_ID';

        protected $guarded = [];
    }
```

3. Create a Model that represents your "Desired" database model.
```php
namespace App;

class DesiredDatabaseModel extends ActualDatabaseModel
{
    // Desired $primaryKey name (PK_Database_ID is the actual PK in the database)
    protected $primaryKey = 'id';

    /**
     * Transformation Mapping of DB Column Names to desired Eloquent Model Attribute Names
     * This variable comes from the SaintSystems\Eloquent\Transformable\Transformable Trait
     * used in the base Model.php
     * @var array
     */
    protected $transform = [
        'id' => 'PK_Database_ID',
        'name' => 'DB_Name',
        'foreign_key_id' => 'FK_DB_Foreign_Key_ID'
    ]; // TransformationMap;
}
```

4. Use your new "Transformed" model the way you want to:
```php
    $model = new DesiredDatabaseModel([
        'id' => 1,
        'name' => 'Name',
        'foreign_key_id' => 2
    ]);

    dd($desiredModel->toArray());
    /*
    Will output the following:
    [
        'id' => 1,
        'name' => 'Name',
        'foreign_key_id' => 2
    ]
    */

    // Now, save the model
    $model->save();
    // Despite using transformed attributes above, the record will still save using the transformed attributes we defined.

    // We can even query the model with our desired/transformed column names
    $model = new DesiredDatabaseModel::where('name','Joe')->orWhere('name','Judy')->get();

    /*
        The call above will result in the following query being run:
        select *
        from "tbl_Database_Table"
        where "DB_Name" = 'Joe' or "DB_Name" = 'Judy'

        But will come back in the following structure:
        [
            [
                'id' => 1,
                'name' => 'Joe',
                'foreign_key_id' => 1
            ],
            [
                'id' => 2,
                'name' => 'Judy',
                'foreign_key_id' => 1
            ]
        ]
    */

```

## Benefits

Using Eloquent Transformable we can build our app around Laravel Eloquent's conventions and use our models as if the underlying database had been built to Laravel's conventions. If we have time and are able to move our database structure to Laravel's conventions eventually, we can simply remove the transformation from our models. This shields us from underlying database changes and allows us to control the appearance of our how our underlying database is exposed in our apps or apis.

## Credits

- [Adam Anderly](https://github.com/anderly)
- [Saint Systems](https://github.com/saintsystems)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
