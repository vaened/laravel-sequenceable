<?php
/**
 * Created by enea dhack - 07/07/17 03:33 PM.
 */

namespace Enea\Tests\Models;

/**
 * Class AutofillingToShowConfiguration.
 *
 * @package Enea\Tests\Models
 * @author enea dhack <enea.so@live.com>
 *
 * Properties.
 *
 * @property string number_string
 *
 */
class AutofillingToStoreConfiguration extends Document
{

    /**
     * Returns the configuration of the sequences.
     *
     * @return array
     */
    public function sequencesSetup()
    {
        return [
            'number_string' => 5
        ];
    }
}
