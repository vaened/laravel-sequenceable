<?php
/**
 * Created by enea dhack - 24/06/17 09:56 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;

class SequenceObserver
{
    /**
     * Create observer.
     *
     * @param SequenceableContract $model
     * @return void
     */
    public function creating(SequenceableContract $model)
    {
        (new Generator($model))->make();
    }
}
