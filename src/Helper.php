<?php
/**
 * Created by enea dhack - 24/06/17 09:56 PM
 */
namespace Enea\Sequenceable;

class Helper
{

    /**
     * Validates that the sequence is within the formats supported by the package
     *
     * @param array|string|int $key
     * @param array|string|int $value
     * @return bool
     */
    public static function isAvailableSequence( $key, $value ): bool
    {
        if( is_string( $key ) ) {
            if ( is_array($value)) {
                $key = key($value);
                $value = current($value);

                return is_integer($value) || is_integer($key) && is_string($value);
            }

            return is_string($value) || is_integer($value) && !is_numeric($key);
        }

        return is_integer($key) && is_string($value);
    }

    /**
     * Get the name of the code that identifies the column that is automatically completed
     * 
     * @param string|int $key
     * @param array|string|int $value
     * @return mixed
     */
    public static function getKeyName($key, $value )
    {
        return is_integer($key) ? $value : $key;
    }

    /**
     * Get the name of the column that is automatically completed
     *
     * @param string|int $key
     * @param array|string|int $value
     * @return string
     */
    public static function getColumnName($key, $value )
    {
        if (is_integer( $key )) {
            return $value;
        }

        if ( is_array( $value )) {
            $key = key( $value );
            return is_string($key) ? $key : current($value);
        }

        return is_integer($value) ? $key : $value;
    }

    /**
     * Get autocomplete size
     *
     * @param string|int $key
     * @param array|string|int $value
     * @return string
     */
    public static function getSize($key, $value)
    {

        if( is_string( $value ) && ( is_integer( $key ) || is_string( $key ) )) {
            return 0;
        }

        if (is_array($value)) {
            $value = current($value);
            return is_integer($value) ? $value : 0;
        }

        return $value;
    }


}