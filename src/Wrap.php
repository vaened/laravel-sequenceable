<?php
/**
 * Created by enea dhack - 26/06/2020 20:12.
 */

namespace Enea\Sequenceable;

use Closure;
use Vaened\SequenceGenerator\Collection as SerieCollection;

class Wrap
{
    protected array $sequences = [];

    protected string $sequence;

    public function __construct(string $sequence)
    {
        $this->sequence = $sequence;
    }

    public static function create(string $sequence, Closure $configure): SerieCollection
    {
        $wrap = new static($sequence);
        $configure($wrap);

        return new SerieCollection(new $sequence, $wrap->sequences);
    }

    public function column(string $column): Serie
    {
        $this->sequences[] = $serie = new Serie($column);
        return $serie;
    }
}
