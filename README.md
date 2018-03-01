# Laravel Sequenceable Package
[![Build Status](https://travis-ci.org/eneav/laravel-sequenceable.svg?branch=master)](https://travis-ci.org/eneav/laravel-sequenceable) [![StyleCI](https://styleci.io/repos/94660091/shield?branch=master)](https://styleci.io/repos/94660091)

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
    public function sequencesSetup()
    {
        return [
            'number'
        ];
    }
}
```

## Configuration
You can configure the sequences of 5 possible shapes, each one follows a logical structure in addition to being able to be combined with each other.
- ##### Simple.
    This is the easiest way, you just need to define the columns you need in the configuration array.

```php
    public function sequencesSetup()
    {
        return [ 'number' ];
    }
```

- ##### Custom key.
    You can set a key for the sequence using the following syntax.

```php
    public function sequencesSetup()
    {
        return [ 
            'custom_key' => [
                'number' 
            ]
        ];
    }
```

- ##### Dynamic key.
    This way you can generate independent sequences according to the key, in this example you can see how it is established that the key will be built according to the type of document, generating as many sequences as document types. You can see an example of this in the `a_sequence_with_a_dynamic_value_is_generated` test located in the file [`GenerateSequenceTest`](https://github.com/eneasdh-fs/laravel-sequenceable/blob/master/tests/GenerateSequenceTest.php)

```php
    public function sequencesSetup()
    {
        return [ 
            $this->sequenceKey() => [
                'number' 
            ]
        ];
    }

    protected function sequenceKey()
    {
        return $this->type;
    }
```

- ##### Fixed length.
    Setting a length in the sequence will be useful when you need to give it a presentation format, for this to work, you must call the column by prefixing a prefix that is set in the configuration file  `config\sequenceable.php` in the key `prefix`.
```php
    public function sequencesSetup()
    {
        return [
            'number' => 8,
        ];
    }
```

- ##### Custom model
    You can encapsulate the sequences in a custom model, this sequence model must be configured so that the sequences can persist in the database. This is shown at the bottom of the documentation.

```php
    public function sequencesSetup()
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

You can review the [`Sequence`](https://github.com/eneasdh-fs/laravel-sequenceable/blob/master/src/Model/Sequence.php) model that is configured in the package to learn more about its operation.

#### Base Structure
You can implement the `SequenceContract` interface or the `Sequence` model's extension to modify the behavior that you consider appropriate.

 Column         | Description
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

#### Sequence storage
If necessary, you can define how the sequences in your database will be stored from `config\sequenceable.php` on the `autoffilling` key, in this way the sequences will be filled with the length you have configured in the configuration of the model at Moment of being persisted in the database.

```php
<?php
return [
     /*
     |---------------------------------------------------------------------------
     | Auto filling
     |---------------------------------------------------------------------------
     | This option allows you to automatically fill the fields in your database, as specified in 
     | the model configuration. If you want to customize the value with which to autofill, 
     | you must change this key by replacing the value with the  character you want, 
     | by default, if it is true, it will autocomplete to zero
     |
     */

    'autofilling' => false,
];
```

#### Prefix
In case you do not want to store sequences with a fixed length in your database but still want to present them in a format, you can simply leave `autofilling` false and call your columns by prefixing a prefix.

```php
<?php
return [
    /*
     |---------------------------------------------------------------------------
     | Prefix
     |---------------------------------------------------------------------------
     | This key specifies the prefix that is used when you need to display the sequence with the 
     | number of characters defined in the configuration, if you do not want to use any 
     | prefix, set this key to null
     */

    'prefix' => 'full_'
];
```

When configuring the sequence with a [`fixed length`](#fixed-length), the call would be made by concatenating `prefix + column`, in this case `full_number`, this will return the sequence belonging to the `number` column that will be filled with zeros The left until it reaches the length set in the model.

```php
    public function store()
    {
        $document = new Document();
        $document->save();
        
        // number = 1
        // full_number = 00000001
        
        return view('document.show', [
            'document_number' => $document->full_number
        ]);
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
