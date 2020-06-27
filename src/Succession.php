<?php
/**
 * Created by enea dhack - 24/06/17 02:01 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;

class Succession
{
    public function from(string $class): SequenceCollection
    {
        $model = new $class;

        if (! $model instanceof SequenceableContract) {
            throw new SequenceException('The model ' . get_class($class) . ' must implement the ' . SequenceableContract::class);
        }

        $sequences = $this->createCollection();

        $model->getGroupedSequences()->each(function (Group $group) use ($model, $sequences) : void {
            $series = $group->sequence()->getSeriesFrom($model->getTable());
            $series->each(fn(SequenceContract $sequence) => $sequences->push($sequence));
        });

        return $sequences;
    }

    protected function createCollection(): SequenceCollection
    {
        return new SequenceCollection();
    }
}
