<?php
/**
 * Created by enea dhack - 07/07/17 01:52 PM.
 */

namespace Enea\Tests\Models;

/**
 * Class AutofillingToShowConfiguration.
 *
 * @package Enea\Tests\Models
 * @author enea dhack <enea.so@live.com>
 *
 * Dynamic properties.
 *
 * @property string full_number
 * @property string full_number_string
 */
class AutofillingToShowConfiguration extends Document
{
    public function sequencesSetup()
    {
        return [
            'custom_key' => ['number' => 8],
            'number_string' => 10,
        ];
    }
}
