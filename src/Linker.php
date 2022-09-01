<?php
/**
 * Created by enea dhack - 25/06/17 02:16 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Enea\Sequenceable\Model\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vaened\SequenceGenerator\Generated;
use Vaened\SequenceGenerator\Generator as SequenceGenerator;
use Vaened\SequenceGenerator\Normalizer;
use Vaened\SequenceGenerator\Serie as BaseSerie;
use function collect;
use function dd;

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
        $collections = $this->model->getGroupedSequences();
        $normalizer = new Normalizer(new Sequence());
        $generator = new SequenceGenerator($normalizer);

        $values = $generator->generate($this->model->getTable(), $collections->toArray());

        collect($values)->each(function (Generated $generated){
            $this->model->setAttribute($generated->getSimpleName(), $generated->getStylizedSequence());
        });

    }

    private function incrementByModel(SequenceContract $orphanedSequence, Collection $series): void
    {
        $normalizer = new Normalizer($orphanedSequence);
        $generator = new SequenceGenerator($normalizer);
        $generator->generate($this->model->getTable(), $series->toArray());
    }

    private function applySerieTo(SequenceGenerator $generator, BaseSerie $serie): void
    {

    }

    private function stylize(Serie $serie, int $sequence): string
    {
        return (new Builder())->build($serie, $sequence);
    }
}
