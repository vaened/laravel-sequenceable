<?php
/**
 * Created by enea dhack - 24/06/17 02:01 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Vaened\SequenceGenerator\Collection as SerieCollection;
use function collect;

class Succession
{
    public function from(string $clazz): SequenceCollection
    {
        $model = new $clazz();

        if (!$model instanceof SequenceableContract) {
            throw new SequenceException("The model '$clazz' must implement the " . SequenceableContract::class);
        }

        $sequences = $this->createCollection();

        $model->getGroupedSequences()->each(function (SerieCollection $collection) use ($model, $sequences): void {
            $series = $collection->getRepository()->getAllFrom($model->getTable());

            collect($series)->each(fn(SequenceContract $sequence) => $sequences->push($sequence));
        });

        return $sequences;
    }

    protected function createCollection(): SequenceCollection
    {
        return new SequenceCollection();
    }
}
