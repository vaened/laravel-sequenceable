<?php
/**
 * Created by eneasdh-fs
 * Date: 11/01/17
 * Time: 10:06 PM
 */

namespace Enea\Sequenceable;

use Illuminate\Database\Eloquent\Model;
use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Illuminate\Support\Collection;

class Builder
{
    /**
     * Model where sequences are generated
     *
     * @var SequenceableContract|Model
     */
    protected $model;

    /**
     * Builder constructor.
     * @param SequenceableContract|Sequenceable $model
     * @throws SequenceException
     */
    public function __construct( SequenceableContract $model = null )
    {
        $this->model = $model;
    }

    /**
     * Establish a model
     *
     * @param SequenceableContract $model
     * @return void
     */
    public function setSequenceableModel( SequenceableContract $model )
    {
        $this->model = $model;
    }

    /**
     * Creates the sequence model and generates the sequence
     *
     * @param $key
     * @param $value
     * @return SequenceContract
     * @throws SequenceException
     */
    public function sequence( $key, $value ): SequenceContract
    {
        if ( ! Helper::isAvailableSequence($key, $value) ) {
            throw new SequenceException( "Wrong sequence configuration format key: $key value: $value" );
        }

        $column = Helper::getColumnName( $key, $value );

        return $this->createSequence( Helper::getKeyName($key, $value), $column );
    }

    /**
     * Builds the sequence model
     *
     * @param $key
     * @param $value
     * @return SequenceContract|Model
     * @throws SequenceException
     */
    public function model($key, $value ): SequenceContract
    {
        if ( ! Helper::isAvailableSequence($key, $value) ) {
            throw new SequenceException( "Wrong sequence configuration format key: $key value: $value" );
        }

        return $this->createModel( Helper::getColumnName( $key, $value ) );
    }

    /**
     * Returns the configured sequence model and, if not defined, takes the default value
     *
     * @param $column
     * @return SequenceContract
     */
    protected function createModel( $column ): SequenceContract
    {
        $instance = $this->model->getSequenceModels( )->search(function ( array $values ) use ( $column ) {
            return in_array($column, $values);
        });

        return new $instance;
    }


    /**
     * Look for the sequence in the table and, if it is not found, generate it
     *
     * @param $id
     * @param $column
     * @return SequenceContract
     * @throws SequenceException
     */
    protected function createSequence( $id, $column ): SequenceContract
    {
        $sequenceable = $this->createModel( $column );
        return $sequenceable->findOrCreate( $id, $this->model->getTable(), $column );
    }

    /**
     * Configuration of the sequences
     *
     * @return Collection
     * @throws SequenceException
     */
    public function getSequencesConfiguration( ): Collection
    {
        return  collect($this->model->sequencesSetup( ));
    }

}