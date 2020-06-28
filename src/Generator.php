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
        ) => $this->increase($group->sequence(), $group->series()));
    }

    private function increase(SequenceContract $sequence, Collection $series): void
    {
        $series->each(fn(Serie $serie) => $this->apply($sequence, $serie));
    }

    private function apply(SequenceContract $sequence, Serie $serie): void
    {
        $sequence = $sequence->locateSerieModel($this->model->getTable(), $serie);
        $number = $this->fill($sequence->next(), $serie->getFixedLength());
        $this->model->setAttribute($serie->getColumnName(), $number);
        $sequence->apply();
    }

    private function fill(string $number, int $length): string
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }
}
