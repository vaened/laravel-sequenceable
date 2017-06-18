<?php
/**
 * Created by eneasdh-fs
 * Date: 08/01/17
 * Time: 10:27 PM
 */

namespace Enea\Sequenceable;


use Enea\Sequenceable\Contracts\SequenceableContract;

class SequenceObserver
{


    /**
     * @param SequenceableContract $model
     * @return void
     */
    public function creating(SequenceableContract $model )
    {
        (new Builder( $model ))->make();
    }


}