<?php
/**
 * Created by enea dhack - 25/06/17 02:16 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Generator
{
    protected SequenceableContract $model;

    public function __construct(SequenceableContract $model)
    {
        if (! $model instanceof Model) {
            throw new SequenceException(get_class($model) . ' Must be an instance of ' . Model::class);
        }

        $this->model = $model;
    }

    public function generate(): void
    {
        $this->model->getGroupedSequences()->each(fn(
            Group $group
        ) => $this->incrementByModel($group->sequence(), $group->series()));
    }

    private function incrementByModel(SequenceContract $orphanedSequence, Collection $series): void
    {
        $series->each(fn(Serie $serie) => $this->applySerieTo($orphanedSequence, $serie));
    }

    private function applySerieTo(SequenceContract $orphanedSequence, Serie $serie): void
    {
        $unusedSequenceNumber = $orphanedSequence->incrementOneTo($this->model->getTable(), $serie);
        $stylizedSequence = $this->stylize($serie, $unusedSequenceNumber);
        $this->model->setAttribute($serie->getColumnName(), $stylizedSequence);
    }

    private function stylize(Serie $serie, int $sequence): string
    {
        return $this->fill($sequence, $serie->getFixedLength());
    }

    private function fill(string $number, int $length): string
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }
}
