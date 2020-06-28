<?php
/**
 * Created by enea dhack - 27/06/2020 0:15.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Support\Collection;

class SequenceCollection
{
    protected array $sequences;

    public function all(): Collection
    {
        return collect($this->sequences);
    }

    public function push(SequenceContract $sequence)
    {
        $this->sequences[$sequence->getColumnKey()] = $sequence;
    }

    public function find(string $column, string $alias = null): ?SequenceContract
    {
        $key = $this->buildColumnName($column, $alias);
        return $this->sequences[$key] ?? null;
    }

    public function isset(string $column, string $alias = null): bool
    {
        $key = $this->buildColumnName($column, $alias);
        return isset($this->sequences[$key]);
    }

    protected function buildColumnName(string $column, string $alias = null): string
    {
        if ($alias != null) {
            return "$column.$alias";
        }

        return $column;
    }
}
