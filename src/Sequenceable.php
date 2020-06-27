<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Model\Sequence;
use Illuminate\Support\Collection;

trait Sequenceable
{
    public function getGroupedSequences(): Collection
    {
        return collect($this->sequencesSetup())->flatten()->groupBy(fn(
            Serie $serie
        ): ?string => $serie->getSequenceClassName())->map(fn(
            Collection $collection,
            ?string $sequence
        ): Group => $this->createGroup($sequence, $collection));
    }

    private function createGroup(string $sequenceClassName, Collection $collection): Group
    {
        if (! empty($sequenceClassName) && class_exists($sequenceClassName)) {
            return new Group(new $sequenceClassName, $collection);
        }

        return new Group($this->getDefaultSequenceModel(), $collection);
    }

    protected function getDefaultSequenceModel(): SequenceContract
    {
        if ($model = config('sequenceable.model', null)) {
            return new $model;
        }

        return new Sequence();
    }

    public static function bootSequenceable(): void
    {
        static::observe(new SequenceObserver());
    }
}
