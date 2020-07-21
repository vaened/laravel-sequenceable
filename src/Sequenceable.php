<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Sequenceable;

use Closure;
use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Model\Sequence;
use Illuminate\Support\Collection;
use LogicException;

trait Sequenceable
{
    public function getGroupedSequences(): Collection
    {
        $series = collect($this->sequencesSetup())->flatten();
        $this->validateDuplicates($series);
        return $series->groupBy($this->modelName())->map($this->toGroup());
    }

    private function validateDuplicates(Collection $series): void
    {
        $series->duplicates(fn(Serie $serie) => $serie->getColumnName())->each(function (string $column): void {
            throw new LogicException("Column '{$column}' should only have one sequence");
        });
    }

    private function modelName(): Closure
    {
        return fn(Serie $serie): ?string => $serie->getSequenceClassName();
    }

    private function toGroup(): Closure
    {
        return fn(Collection $collection, ?string $sequence): Group => $this->createGroup($sequence, $collection);
    }

    private function createGroup(?string $sequenceClassName, Collection $collection): Group
    {
        if (! empty($sequenceClassName) && class_exists($sequenceClassName)) {
            return new Group(new $sequenceClassName(), $collection);
        }

        return new Group($this->getDefaultSequenceModel(), $collection);
    }

    protected function getDefaultSequenceModel(): SequenceContract
    {
        $model = config('sequenceable.model') ?: Sequence::class;
        return new $model();
    }

    public static function bootSequenceable(): void
    {
        static::creating(fn(SequenceableContract $model) => (new Generator($model))->generate());
    }
}
