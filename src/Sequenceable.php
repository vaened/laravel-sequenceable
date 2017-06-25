<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM
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
 *
 */
trait Sequenceable
{

    /**
     * Sequence configuration
     *
     * @var Collection
     * */
    protected $sequencesConfiguration;

    /**
     * Sequence models
     *
     * @var Collection
     * */
    protected $instances;

    /**
     * The constructor is used to compile the configuration of the sequences in
     * different collections so as to facilitate the use and to save resources
     *
     * Sequenceable constructor.
     */
    public function __construct( )
    {
        $this->compileSequences( );
    }

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
     * @return Collection
     */
    private final function getAutocompletableSequence()
    {
        $collection = collect( );

        foreach ( $this->getSequencesConfiguration( ) as $key => $sequence) {

            if (is_array($sequence)) {
                $key = key($sequence);
                $sequence = current($sequence);
            }

            $collection->put($key, $sequence);
        }


        return $collection;
    }

    /**
     * Returns, only if defined, the custom instances
     *
     * @return Collection
     * */
    public function getSequencesInstances( ): Collection
    {
        return $this->instances;
    }

    /**
     * Returns the sequences defined in the model
     *
     * @return Collection
     * @throws SequenceException
     */
    public function getSequencesConfiguration( ): Collection
    {
        if ( ! $this instanceof  Model ) {
            throw new SequenceException( static::class  . ' Must be an instance of ' . Model::class);
        }

        return $this->sequencesConfiguration;
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
     * Orders the sequence configuration by separating the
     * columns and instances of the sequenceable contract
     *
     * @return void
     */
    protected function compileSequences( )
    {
        $this->sequencesConfiguration = collect( );
        $this->instances = collect( );

        collect($this->sequencesSetup( ))->each( function ( $values, $key  ) {

            $sequences = array( );

            if ( ! class_exists( $key )) {
                $this->sequencesConfiguration->put( $key, $values );
            } else {

                foreach ( (array) $values as $k => $value ) {

                    if (is_numeric( $k )) {
                        $this->sequencesConfiguration->push($value);
                    } else {
                        $this->sequencesConfiguration->put( $k, $value );
                    }

                    $sequences[ ] = is_array( $value ) ? key( $value ) : (
                    is_numeric( $value ) ? $k: $value
                    );
                }

                $this->instances->put( $key, $sequences );
            }
        });
    }


}