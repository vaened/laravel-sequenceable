<?php
/**
 * Created by eneasdh-fs - 11/01/17 10:00 PM.
 */

namespace Enea\Sequenceable\Contracts;

use Illuminate\Support\Collection;

interface SequenceContract
{
    /**
     * Increase sequence by one and return it.
     *
     * @return int
     */
    public function next();

    /**
     * Decrements the sequence by one and return it.
     *
     * @return int
     */
    public function prev();

    /**
     * Gets the current sequence.
     *
     * @return int
     * */
    public function current();

    /**
     * Returns the name of the field that stores the column to which the sequence belongs.
     *
     * @return string
     * */
    public function getColumnKey();

    /**
     * Filters only the tables that are passed by parameter.
     *
     * @param string $table
     * @return Collection
     */
    public function source($table);

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param  string|int $key
     * @param  string $table
     * @param  string $column
     * @return SequenceContract
     */
    public function findOrCreate($key, $table, $column);

}