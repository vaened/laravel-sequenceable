<?php
/**
 * Created by enea dhack - 25/06/17 02:31 PM
 */

namespace Enea\Tests\Models;


class BasicSequenceConfiguration extends Document
{
    public function sequencesSetup( ): array
    {
        return [ 'number', 'number_string' ];
    }

}