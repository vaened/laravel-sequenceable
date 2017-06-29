# Laravel Sequenceable Package
This package provides functionality for creating database sequences.
## How to Install
1. It is recommended to install this package through the composer.
```sh
$ composer require enea/laravel-sequenceable
```
2. Add the provider to the `providers` key in `config/app.php`.
```php
'providers' => [
    // ...
    Enea\Sequenceable\Providers\SequenceableServiceProvider::class,
    // ...
],
```
3. Publish the configuration files.1
```sh
$ php artisan vendor:publish --provider='Enea\Sequenceable\Providers\SequenceableServiceProvider'
```
4. Run the table migration for the sequences.
```sh
$ php artisan migrate
```

## Basic Usage
To start with the package is as simple as using the `Sequenceable` trait  and implementing the `SequenceableContract` interface, after which you only have to specify which fields will auto-increment
```php
<?php namespace App;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Sequenceable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model implements SequenceableContract
{

    use Sequenceable;

    /**
     * Returns the configuration of the sequences
     *
     * @return array
     */
    public function sequencesSetup(): array
    {
        return [
            'number'
        ];
    }
}
```

## Configuration
The sequences can be configured in several ways.
1. The simplest form.
```php
    public function sequencesSetup(): array
    {
        return [ 'number' ];
    }
```
2. With a custom key.
```php
    public function sequencesSetup(): array
    {
        return [ 
            'custom_key' => [
                'number' 
            ]
        ];
    }
```
2. With a dynamic key, sequences are generated depending on the origin.
```php
    public function sequencesSetup(): array
    {
        return [ 
            $this->makeType() => [
                'number' 
            ]
        ];
    }

    protected function makeType()
    {
        return $this->origin;
    }
```
4. Everything can be encapsulated in a custom sequence model.
```php
    public function sequencesSetup(): array
    {
        return [ 
            CustomSequence::class => [
                // sequences
            ]
        ];
    }
```
## Facade
There is a 'facade' to facilitate the obtaining of the sequences that belong to a model.
```php
Succession::on(Document::class);
```
This returns an instance of `Illuminate\Support\Collection` where each key is represented by the `column_key` defined in the model configuration, and the value is an instance of the model of the sequence to which it belongs.

## Customize
The package has a configuration that generates the sequences with the following structure.

 id       | sequence | source    | column_key | description      | created_at          | updated_at          
----------|----------|-----------|------------|------------------|---------------------|---------------------
 37cc068a |        1 | documents | number     | documents.number | 2017-06-29 18:40:44 | 2017-06-29 18:40:44 

You can review the [Sequence](https://github.com/eneasdh-fs/laravel-sequenceable/blob/master/src/Model/Sequence.php) model that is configured in the package to learn more about its operation.

#### Base Structure
You can implement the `SequenceContract` interface or the `Sequence` model's extension to modify the behavior that you consider appropriate.

 campo          | Descripción
----------------|------------------------------------------------------------------------------------
 **id**         | It is generated on the basis of the union of the table, column and key.
**sequence**    | Stores the current field sequence.
**source**      | Contains the name of the table.
**column_key**  | Name and key of the concatenated column.
**description** | Contains the description of the sequence.
**created_at**  | Indicates the date and time the sequence was created.
**updated_at**  | Indicates the last time the sequence is updated.

#### Model
You can change the default sequence model of `config\sequenceable.php` in the `model` key.
```php
<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Sequence Model
    |--------------------------------------------------------------------------
    |
    | This key defines the base sequence model that will be used to generate the autoincrementable 
    | values, you can modify this key and define your own sequence model whenever 
    | you implement the sequenceContract interface or extend the base model
    */
   'model' => \Enea\Sequenceable\Model\Sequence::class,
];
```
Or explicitly specify the model you want to use with certain fields, you can achieve this from the configuration of the sequences in your model.
```php
    public function sequencesSetup(): array
    {
        return [ 
            CustomSequence::class => [
                // sequences
            ]
        ];
    }
```
#### Storage table
If you already have a table that stores the sequences in your database, you may need a different sequence model than the one already configured.

There are some fields you should store in your sequence table such as:
1. The key to the sequence.
2. The contents of the column plus the key, separated by a period.
3. The name of the table to which the sequence belongs.

You can see an example of this in the 'test' folder and look for the files `Models\CustomSequence.php` and `Migrations/2017_04_23_200525_create_custom_sequences_table.php`

## More documentation
You can find a lot of comments within the source code as well as the tests located in the `tests` directory.
