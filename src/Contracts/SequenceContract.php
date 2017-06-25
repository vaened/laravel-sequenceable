<?php
/**
 * Created by eneasdh-fs
 * Date: 11/01/17
 * Time: 10:00 PM
 */

namespace Enea\Sequenceable\Contracts;


interface SequenceContract
{
    /**
     * Increase sequence by one and return it
     *
     * @return integer
     */
    public function next( ): int;

    /**
     * Decrements the sequence by one and return it
     *
     * @return integer
     */
    public function prev( ): int;

    /**
     * Gets the current sequence
     *
     * @return integer
     * */
    public function current( ): int;

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  string|integer $key
     * @param  string $table
     * @param  string $column
     *  @return SequenceContract
     */
    public function findOrCreate( $key, $table, $column ): SequenceContract;


}