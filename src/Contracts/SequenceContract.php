<?php
/**
 * Created by eneasdh-fs
 * Date: 11/01/17
 * Time: 10:00 PM
 */

namespace Enea\Sequenceable\Contracts;

use Illuminate\Support\Collection;

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
     * Returns the name of the field that stores the column to which the sequence belongs
     *
     * @return string
     * */
    public function getColumnKey(): string;

    /**
     * Filters only the tables that are passed by parameter
     *
     * @param string $table
     * @return Collection
     */
    public function source(string $table): Collection;

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