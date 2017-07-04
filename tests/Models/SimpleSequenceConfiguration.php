<?php
/**
 * Created by enea dhack - 25/06/17 02:33 PM.
 */

namespace Enea\Tests\Models;

class SimpleSequenceConfiguration extends Document
{
    public function sequencesSetup()
    {
        return ['number'];
    }
}