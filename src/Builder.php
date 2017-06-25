<?php
/**
 * Created by eneasdh-fs
 * Date: 11/01/17
 * Time: 10:06 PM
 */

namespace Enea\Sequenceable;


use Enea\Sequenceable\Model\Sequence;
use Illuminate\Database\Eloquent\Model;
use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;

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
    public function __construct( SequenceableContract $model )
    {
        if  ( ! $model instanceof  Model) {
            throw  new SequenceException( get_class( $model )  . ' Must be an instance of ' . Model::class);
        }

        $this->model = $model;
    }

    /**
     * Build sequence for new resource
     *
     * @throws SequenceException
     */
    public function __invoke()
    {
        $this->make( );
    }


    /**
     * Build sequence for new resource
     *
     * @throws SequenceException
     */
    public final function make( )
    {
        foreach ($this->model->getSequencesConfiguration() as $key => $size ) {

            if ( ! Helper::isAvailableSequence($key, $size) ) {
                throw new SequenceException( "Wrong sequence configuration format key: $key value: $size" );
            }

            $column = Helper::getColumnName($key, $size);

            $sequence = $this->createSequence( Helper::getKeyName($key, $size), $column )->next( );

            if ($this->isAutoCompletable( ) ) {
                $sequence = $this->model->autocomplete( $sequence, Helper::getSize($key, $size) );
            }

            $this->model->setAttribute($column, $sequence );
        }

    }

    /**
     * @param $column
     * @return SequenceContract
     * @throws SequenceException
     */
    protected function sequenceModel( $column )
    {
        $instance = $this->model->getSequencesInstances( )->search(function ( array $values ) use ( $column ) {
            return in_array($column, $values);
        });

        if ( $instance ) {
            return new $instance;
        }

        if ( $model = config( 'sequenceable.model' )) {
            return new $model;
        }

        return new Sequence( );
    }

    /**
     * Returns true if the sequence is to be filled
     *
     * @return bool
     */
    protected function isAutoCompletable( ): bool
    {
        return config('sequenceable.autofilling', false);
    }

    /**
     * To obtain the sequential model
     *
     * @param $id
     * @param $column
     * @return SequenceContract
     * @throws SequenceException
     */
    protected function createSequence( $id, $column )
    {
        $sequenceable = $this->sequenceModel( $column );

        if( ! $sequenceable instanceof  SequenceContract ) {
            throw  new SequenceException( 'The sequence must be an instance of ' . SequenceContract::class );
        }

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