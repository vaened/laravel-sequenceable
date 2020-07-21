# Laravel Sequenceable Package

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/vaened/laravel-sequenceable/badges/quality-score.png?b=upgrade)](https://scrutinizer-ci.com/g/vaened/laravel-sequenceable/?branch=upgrade)  [![Build Status](https://travis-ci.org/vaened/laravel-sequenceable.svg?branch=master)](https://travis-ci.org/vaened/laravel-sequenceable)  [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md) 

Laravel Sequenceable is a library to generate and manage sequences for laravel models.

```php
// simple sequence
Serie::lineal('document_number');

// sequence with an alias
Serie::lineal('document_number')->alias('invoice');

// sequence with an alias and fixed length
Serie::lineal('document_number')->alias('invoice')->length(8);
```

## Installation
Laravel Sequenceable requires PHP 7.4. This version supports Laravel 7

To get the latest version, simply require the project using Composer:
```sh
$ composer require enea/laravel-sequenceable
```

Once installed, if you are not using automatic package discovery, then you need to register the [`Enea\Sequenceable\SequenceableServiceProvider`](https://github.com/vaened/laravel-sequenceable/blob/master/src/SequenceableServiceProvider.php) service provider in your `config/app.php`.

Now. Publish the configuration file.

```sh
$ php artisan vendor:publish --provider='Enea\Sequenceable\SequenceableServiceProvider'
```
And finally run migrations.

```sh
$ php artisan migrate
```

## Basic Usage
Getting started with the library is as simple as using the `Sequenceable` trait and implementing the `SequenceableContract` interface, after that you only need to specify the sequences you want to generate.
```php
<?php namespace App;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Sequenceable;
use Enea\Sequenceable\Serie;
use Illuminate\Database\Eloquent\Model;

class Document extends Model implements SequenceableContract
{
    use Sequenceable;

    public function sequencesSetup(): array
    {
      return [
          Serie::lineal('document_number')
      ];
    }
}
```

## Advanced
We exemplify all the options to generate a `sequence` with the case of a payment document.

- To start, we need a column to store the sequence, and for this we will use a column called `number`.

```php
    public function sequencesSetup(): array
    {
        return [ Serie::lineal('document_number') ];
    }
```
- Now that we have the column defined, we realize that we need to create a separate sequence for each type of document. For this problem, the library offers the possibility of adding an alias for the column.

```php
    public function sequencesSetup(): array
    {
        return [ 
            Serie::lineal('document_number')->alias($this->type())
        ];
    }

  	protected function type(): string
    {
        return $this->payment_document_type;
    }
```
- Everything is fine, but now we want the sequences not to be saved in a numeric value, but instead to be a text string with a fixed length of 10.

```php
    public function sequencesSetup(): array
    {
        return [ 
            Serie::lineal('document_number')->alias($this->type())->length(10)
        ];
    }
```
- Concluding, we could also say that we do not want to use the default table for sequences and we need a special table to store the payment sequences, for this you have to [create your own sequence table](#customize).

  > We can wrap a block of sequences using the class `Enea\Sequenceable\Wrap::create`

```php
    public function sequencesSetup(): array
    {
        return [ 
            Wrap::create(PaymentSequence::class, 
                         fn(Wrap $wrap) => Serie::lineal('document_number')->alias($this->type())->length(10))
        ];
    }
```
## List
To retrieve all the sequences of a model, you can use the `Enea\Sequenceable\Facades\Succession` facade which is linked to the [`Enea\Sequenceable\Succession`](https://github.com/vaened/laravel-sequenceable/blob/master/src/Succession.php) class.

```php
$collection = Succession::from(Document::class);
```
This returns an instance of [`Enea\Sequenceable\SequenceCollection`](https://github.com/vaened/laravel-sequenceable/blob/master/src/SequenceCollection.php) . With which you can do things like:

```php
// return all sequences
$collection->all();

// find sequence by name
$collection->find('document_number', 'invoice');
```

## Config

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
    | you implement the SequenceContract interface or extend the base model
    */
   'model' => \Enea\Sequenceable\Model\Sequence::class,
];
```
Or explicitly specify the model you want to use with certain fields, you can achieve this from the configuration of the sequences in your model.
```php
    public function sequencesSetup(): array
    {
        return [ 
             Wrap::create(CustomSequence::class, function(Wrap $wrap): void {
                $wrap->column('column-name');                
                $wrap->column('another-column-name');
                //..
            }),
        ];
    }
```

## Customize
if you already have a model to store your sequences, you need to implement the [`Enea\Sequenceable\Contracts\SequenceContract`](https://github.com/vaened/laravel-sequenceable/blob/master/src/Contracts/SequenceContract.php) interface, or extend the default model [`Enea\Sequenceable\Model\Sequence`](https://github.com/vaened/laravel-sequenceable/blob/master/src/Model/Sequence.php).

In case you have your own sequence model, there are some fields that you should store in its sequence table:

1. The **column ID**, and this is obtained by concatenating the column name and alias.
2. The name of the **table** to which the sequence belongs.
3. An integer type **sequence**.

### Example

To better exemplify this, we will use the default [`Sequence`](https://github.com/vaened/laravel-sequenceable/blob/master/src/Model/Sequence.php) model.


This model comes with a default configuration.

 id       | sequence | source    | column_id              | created_at          | updated_at          
 -------- | -------- | --------- | ---------------------- | ------------------- | ------------------- 
 e4910d63 | 1        | documents | document_number.invoce | 2020-07-03 18:40:44 | 2020-07-03 18:40:44 

#### Migration

The table structure has the required fields, you can see the migration in [`CreateSequencesTable`](https://github.com/vaened/laravel-sequenceable/blob/master/database/migrations/2017_04_23_200525_create_sequences_table.php)

 Column         | Description								| Required 
------------------------|---------------------------------------------------------------------------------|:-:
 **id**      | It is generated on the basis of the union of the table, column and alias 										|:x:
**sequence**    | Stores the last value in the sequence |:white_check_mark:
**source**          | Stores the table name | :white_check_mark:
**column_id**   | Concatenated name and alias | :white_check_mark:
**created_at**  | Indicates the date and time the sequence was created |:x:
**updated_at**  | Indicates the last time the sequence is updated |:x:

You can see another example of this in the `test` folder and look for the files [`CustomSequence.php`](https://github.com/vaened/laravel-sequenceable/blob/master/tests/Models/CustomSequence.php) and [`migrations/2017_04_23_200525_create_custom_sequences_table.php`](https://github.com/vaened/laravel-sequenceable/blob/master/tests/migrations/2017_04_23_200525_create_custom_sequences_table.php)

## More documentation

You can find a lot of comments within the source code as well as the tests located in the `tests` directory.
