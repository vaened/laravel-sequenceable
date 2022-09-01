<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Illuminate\Support\Collection;
use Vaened\SequenceGenerator\Generator as SequenceGenerator;
use Vaened\SequenceGenerator\Normalizer;
use function collect;
use function resolve;

trait Sequenceable
{
    public function getGroupedSequences(): Collection
    {
        $normalizer       = resolve(Normalizer::class);
        $serieCollections = $normalizer->normalize($this->sequencesSetup());

        return collect($serieCollections);
    }

    public static function bootSequenceable(): void
    {
        static::creating(static fn(SequenceableContract $model) => (new Linker(
            resolve(SequenceGenerator::class), $model
        ))->bind());
    }
}
