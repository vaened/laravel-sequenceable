<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Exceptions\SequenceException;
use Enea\Sequenceable\Model\Sequence;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait Sequenceable
{
    /**
     * Model builds key.
     *
     * @param int|string $key
     * @param string $column
     * @return string
     * @return string
     */
    public function makeKey($key, $column = null)
    {
        $sequence = $this->getAutocompletableSequence();

        if (! empty($column) && $sequence->has($column)) {
            return $this->autocomplete($key, $sequence->get($column));
        }

        return $key;
    }

    /**
     * Fill a string as requested.
     *
     * @param int|string $key
     * @param int $size
     * @return string
     */
    public function autocomplete($key, $size)
    {
        if ($size > 1 && is_numeric($key)) {
            $key = str_pad($key, $size, '0', STR_PAD_LEFT);
        }

        return $key;
    }

    /**
     * @return Collection
     */
    private function getAutocompletableSequence()
    {
        $collection = collect();

        foreach ($this->getSequencesConfiguration() as $key => $sequence) {
            if (is_array($sequence)) {
                $key = key($sequence);
                $sequence = current($sequence);
            }

            $collection->put($key, $sequence);
        }

        return $collection;
    }

    /**
     * Returns, only if defined, the custom instances.
     *
     * @return Collection
     * */
    public function getSequenceModels()
    {
        $instances = collect();
        $common = array();
        foreach ($this->sequencesSetup() as $key => $values) {
            $sequences = array();
            if (! class_exists($key)) {
                $common[] = Helper::getColumnName($key, $values);
            } else {
                foreach ((array) $values as $k => $value) {
                    $sequences[] = Helper::getColumnName($k, $value);
                }

                $instances->put($key, $sequences);
            }
        }

        $instances->put($this->defaultSequenceName(), $common);

        return $instances;
    }

    /**
     * Returns the sequences defined in the model.
     *
     * @throws SequenceException
     * @return Collection
     */
    public function getSequencesConfiguration()
    {
        if (! $this instanceof  Model) {
            throw new SequenceException(static::class . ' Must be an instance of ' . Model::class);
        }

        $sequencesConfiguration = collect();

        foreach ($this->sequencesSetup() as $key => $values) {
            if (! class_exists($key)) {
                $sequencesConfiguration->put($key, $values);
            } else {
                foreach ((array) $values as $k => $value) {
                    if (is_numeric($k)) {
                        $sequencesConfiguration->push($value);
                    } else {
                        $sequencesConfiguration->put($k, $value);
                    }
                }
            }
        }

        return $sequencesConfiguration;
    }

    /**
     * Add observer.
     *
     * @return void
     */
    public static function bootSequenceable()
    {
        if (static::isSequenceableAvailable()) {
            static::observe(new SequenceObserver());
        }
    }

    /**
     * Modify this method if necessary.
     *
     * @return bool
     */
    protected static function isSequenceableAvailable()
    {
        return true;
    }

    /**
     * Returns the full path of the default sequence model.
     *
     * @return string
     */
    protected function defaultSequenceName()
    {
        if ($model = config('sequenceable.model')) {
            return $model;
        }

        return Sequence::class;
    }
}
