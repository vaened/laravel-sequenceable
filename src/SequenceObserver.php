<?php
/**
 * Created by enea dhack - 24/06/17 09:56 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;

class SequenceObserver
{
    public function creating(SequenceableContract $model): void
    {
        (new Generator($model))->generate();
    }
}
