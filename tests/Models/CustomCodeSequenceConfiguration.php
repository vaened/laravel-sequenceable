<?php
/**
 * Created by enea dhack - 25/06/17 02:31 PM.
 */

namespace Enea\Tests\Models;

class CustomCodeSequenceConfiguration extends Document
{
    public function sequencesSetup()
    {
        return [
            'custom_number_code' =>  'number',
            'custom_number_string_code' => ['number_string'],
        ];
    }
}