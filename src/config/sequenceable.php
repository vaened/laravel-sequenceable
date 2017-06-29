<?php
/**
 * Created by eneasdh-fs
 * Date: 23/04/17
 * Time: 04:06 PM
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Sequence Model
    |--------------------------------------------------------------------------
    |
    | This key defines the base sequence model that will be used to generate the autoincrementable
    | values, you can modify this key and define your own sequence model whenever
    | you implement the sequenceContract interface or extend the base model
    |
    */
   'model' => \Enea\Sequenceable\Model\Sequence::class,

    /*
     |---------------------------------------------------------------------------
     | Auto filling
     |---------------------------------------------------------------------------
     | This option allows you to automatically fill the fields in your database, as specified in the model configuration.
     | If you want to customize the value with which to autofill, you must change this key by replacing the
     | value with the character you want, by default, if it is true, it will autocomplete to zero
     |
     */
    'autofilling' => false,

];