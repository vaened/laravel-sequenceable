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
     * Superficially increases by one.
     *
     * @return int
     */
    public function next(): int;

    /**
     * Superficially decreases by one.
     *
     * @return int
     */
    public function prev(): int;

    /**
     * Returns the current sequence in use.
     *
     * @return int
     * */
    public function current(): int;

    /**
     * returns the ID of the column, which consists of column and alias.
     *
     * @return string
     * */
    public function getColumnID(): string;

    /**
     * Returns the name of the table.
     *
     * @return string
     */
    public function getSourceValue(): string;

    /**
     * Filter all sequences from a given table.
     *
     * @param string $table
     * @return Collection
     */
    public function getSeriesFrom(string $table): Collection;

    /**
     * Increment the sequence by one and store its value in the database.
     *
     * @param string $table
     * @param Serie $serie
     * @return int
     */
    public function incrementOneTo(string $table, Serie $serie): int;
}
