<?php
/**
 * Created by eneasdh-fs
 * Date: 08/01/17
 * Time: 10:26 PM
 */

namespace Enea\Sequenceable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Exceptions\SequenceException;
use Enea\Sequenceable\Model\Sequence;


/**
 *
 * Class Sequenceable
 *
 * @package Core\Resources\Sequenceable
 *
 * @property  array sequences
 * @property  array bindings
 * @property  string sequence_model
 *
 */
trait Sequenceable
{

    /**
     * Model builds key
     *
     * @param integer|string $key
     * @param string $column
     * @return string
     * @return string
     */
    public function makeKey( $key, $column = null )
    {
        $sequence = $this->getAutocompletableSequence();

        if( ! empty($column) && $sequence->has($column)  ) {
            return $this->autocomplete( $key, $sequence->get($column));
        }

        return $key;
    }


    /**
     * @param integer|string $key
     * @param integer $size
     * @return string
     */
    public function autocomplete($key, $size )
    {
        if ( $size > 1 && is_numeric( $key )) {
            $key = str_pad( $key, $size, '0', STR_PAD_LEFT );
        }

        return $key;
    }

    /**
     * Sequence settings by model
     * @return array
     */
    public function getSequenceBindings()
    {
        return isset($this->bindings) ? $this->bindings : array();
    }

    /**
     * Gets the sequence model
     *
     * @return SequenceContract
     * @throws SequenceException
     */
    public function getSequenceModel()
    {
        if( isset( $this->sequence_model ) ) {
            return new $this->sequence_model;
        }

        if ( $model = config( 'sequenceable.model' )) {
            return new $model;
        }

        return new Sequence( );
    }



    /**
     * Configuration of the sequences
     *
     * @return Collection
     * @throws SequenceException
     */
    public final function getSequencesConfig( )
    {
        if ( ! $this instanceof  Model ) {
            throw  new SequenceException( static::class  . ' Must be an instance of ' . Model::class);
        }

        if( method_exists($this, 'sequences') ) {
            $sequences = $this->sequences();
        }else if (  property_exists($this, 'sequences')) {
            $sequences = $this->sequences;
        }else{
            $sequences = [ $this->getKeyName( )  ];
        }

        return  collect($sequences);
    }




    /**
     * @return Collection
     */
    private final function getAutocompletableSequence()
    {
        $collection = collect( );

        foreach ( $this->getSequencesConfig( ) as $key => $sequence) {

            if (is_array($sequence)) {
                $key = key($sequence);
                $sequence = current($sequence);
            }

            $collection->put($key, $sequence);
        }


        return $collection;
    }



    /**
     * Modify this method if necessary
     *
     * @return bool
     */
    protected static function isSequenceableAvailable()
    {
        return true;
    }

    /**
     *
     * @return void
     */
    public static function bootSequenceable()
    {
        if ( static::isSequenceableAvailable()) {
            static::observe(new SequenceObserver());
        }
    }

}