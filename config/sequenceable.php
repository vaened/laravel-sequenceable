<?php
/**
 * Created by eneasdh-fs - 23/04/17 04:06 PM.
 */

return [

    /*
    |--------------------------------------------------------------------------
    | ID Hash
    |--------------------------------------------------------------------------
    |
    | This is used to determine the default hashing method
    | applied to the generated IDs within the default sequences model.
    |
    */
    'hash' => 'sha256',

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

    'model' => Enea\Sequenceable\Model\Sequence::class
];
