<?php
/**
 * Created by enea dhack - 24/06/17 02:01 PM
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Succession
{
    /**
     * @var Builder
     */
    private $builder;

    /**
     * Succession constructor.
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param Model|SequenceableContract $class
     * @throws SequenceException
     * @return Collection
     */
    public function on($class)
    {
        if ( is_string($class)) {
            $class  = new $class;
        }

        if (! $class instanceof SequenceableContract) {
            throw new SequenceException('The model '. get_class($class) . ' must implement the '. SequenceableContract::class);
        }

        $this->builder->setSequenceableModel($class);

        $sequences = collect();

        $class->getSequenceModels()->each(function ($value, $key) use ($class, $sequences) {
            /** @var SequenceContract $sequence*/
            $sequence = new $key;

            $sequence->source($class->getTable())->each(function(SequenceContract $sequence) use ($sequences) {
                $sequences->put($sequence->getColumnKey(), $sequence);
            });
        });

        return $sequences;
    }
}
