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

class Builder
{
    /**
     * @var SequenceableContract|Model
     */
    private $model;


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
        $sequences = $this->model->getSequencesConfig( );

        foreach ($sequences as $key => $size ) {

            if ( ! $this->isAvailable($key, $size) ) {
                throw new SequenceException( "Wrong sequence configuration format key: $key value: $size" );
            }

            $column = $this->getColumnName($key, $size);

            $sequence = $this->sequence( $this->getKeyName($key, $size), $column )->next( );

            $this->model->setAttribute($column, $this->model->autocomplete( $sequence, $this->getSize($key, $size) ));
        }

    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function getKeyName($key, $value )
    {
        if( is_array($value) || is_integer($value)){
            return $key;
        }

        return $value;
    }

    /**
     * @param string|integer $key
     * @param string|array $value
     * @return string
     */
    protected function getColumnName($key, $value )
    {
        $isValueArray = is_array($value);

        if ( is_integer($key) && ! $isValueArray) {
            return $value;
        }

        if( $isValueArray ) {
            $key = key($value);
            return is_string($key) ? $key : current($value);
        }

        return $key;
    }

    /**
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function getSize($key, $value)
    {
        if(is_integer($key) && ! is_array($value)) {
            return 0;
        }

        if (is_array($value)) {
            $value = current($value);
            return is_integer($value) ? $value : 0;
        }

        return $value;
    }


    /**
     * @param $key
     * @param $value
     * @return bool
     */
    protected function isAvailable($key, $value )
    {
        if( is_string( $key ) || is_numeric($key) ) {
            if ( is_array($value)) {

                $key = key($value);
                $value = current($value);

                return (is_string($key) && is_integer($value)) || (is_integer($key) && is_string($value));
            }

            return is_integer($value) && !is_numeric($key);
        }

        return is_integer($key) && is_string($value);
    }


    /**
     * @param $column
     * @return SequenceContract
     * @throws SequenceException
     */
    protected final function sequenceModel($column )
    {

        foreach ($this->model->getSequenceBindings() as $class => $columns ) {
            if ( array_search($column, $columns) ) {
                return new $class;
            }
        }

        return $this->model->getSequenceModel( );
    }

    /**
     * To obtain the sequential model
     *
     * @param $id
     * @param $column
     * @return SequenceContract
     * @throws SequenceException
     */
    protected final function sequence($id, $column )
    {
        $sequenceable = $this->sequenceModel( $column );

        if( ! $sequenceable instanceof  SequenceContract ) {
            throw  new SequenceException( 'The sequence must be an instance of ' . SequenceContract::class );
        }

        return $sequenceable->findOrCreate( $id, $this->model->getTable(), $column );
    }

}