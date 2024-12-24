<?php
/**
 * Created by enea dhack - 25/06/17 02:16 PM.
 */

namespace Vaened\Sequenceable;

use Vaened\Sequenceable\Contracts\SequenceableContract;
use Vaened\SequenceGenerator\Generated;
use Vaened\SequenceGenerator\Generator;

use function collect;

class Linker
{
    public function __construct(
        private readonly Generator            $generator,
        private readonly SequenceableContract $model
    )
    {
    }

    public function bind(): void
    {
        $collections = $this->model->getGroupedSequences();
        $values      = $this->generator->generate($this->model->getTable(), $collections->toArray());

        collect($values)->each($this->setSequence());
    }

    private function setSequence(): callable
    {
        return fn(Generated $generated) => $this->model->setAttribute(
            $generated->getSerieName(),
            $generated->getStylizedSequence()
        );
    }
}
