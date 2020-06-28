<?php
/**
 * Created by eneasdh-fs - 11/01/17 10:00 PM.
 */

namespace Enea\Sequenceable\Contracts;

use Enea\Sequenceable\Serie;
use Illuminate\Support\Collection;

interface SequenceContract
{
    /**
     * Increase sequence by one and return it.
     *
     * @return int
     */
    public function next(): int;

    /**
     * Decrements the sequence by one and return it.
     *
     * @return int
     */
    public function prev(): int;

    /**
     * Gets the current sequence.
     *
     * @return int
     * */
    public function current(): int;

    /**
     * Returns the name of the field that stores the column to which the sequence belongs.
     *
     * @return string
     * */
    public function getColumnID(): string;

    /**
     * Filters only the tables that are passed by parameter.
     *
     * @param string $table
     * @return Collection
     */
    public function getSeriesFrom(string $table): Collection;

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param string $table
     * @param Serie $serie
     * @return SequenceContract
     */
    public function locateSerieModel(string $table, Serie $serie): SequenceContract;

    /**
     * Changes made to the sequence are saved in the database.
     *
     * @return void
     */
    public function apply(): void;
}
