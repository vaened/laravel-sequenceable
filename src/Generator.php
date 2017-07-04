<?php
/**
 * Created by enea dhack - 25/06/17 02:16 PM.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Illuminate\Database\Eloquent\Model;

class Generator
{
    /**
     * Model where sequences are generated.
     *
     * @var SequenceableContract|Model
     */
    protected $model;

    /**
     * Construct of the sequence model.
     *
     * @var Builder
     * */
    protected $builder;


    /**
     * Builder constructor.
     *
     * @param SequenceableContract|Sequenceable $model
     * @throws SequenceException
     */
    public function __construct(SequenceableContract $model)
    {
        if  (! $model instanceof  Model) {
            throw new SequenceException(get_class($model) . ' Must be an instance of ' . Model::class);
        }

        $this->model = $model;
        $this->builder = new Builder($this->model);
    }

    /**
     * Build sequence for new resource.
     *
     * @throws SequenceException
     */
    public function __invoke()
    {
        $this->make();
    }

    /**
     * Build sequence for new resource.
     *
     * @throws SequenceException
     * @return void
     */
    public function make()
    {
        foreach ($this->model->getSequencesConfiguration() as $key => $value) {

            $sequence = $this->builder->sequence($key, $value)->next();

            if ($this->isAutoCompletable()) {
                $sequence = $this->model->autocomplete($sequence, Helper::getSize($key, $value));
            }

            $this->model->setAttribute(Helper::getColumnName($key, $value), $sequence);
        }
    }

    /**
     * Returns true if the sequence is to be filled.
     *
     * @return bool
     */
    protected function isAutoCompletable()
    {
        return config('sequenceable.autofilling', false);
    }
}