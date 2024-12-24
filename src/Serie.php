<?php
/**
 * Created by enea dhack - 26/06/2020 15:07.
 */

namespace Vaened\Sequenceable;

use Vaened\SequenceGenerator\Serie as BaseSerie;
use Vaened\SequenceGenerator\Stylists\FixedLength;
use Vaened\SequenceGenerator\Stylists\Prefixed;

class Serie extends BaseSerie
{
    private array $stylists = [];

    public static function lineal(string $column): static
    {
        return new self($column);
    }

    public function prefixed(string $prefix): static
    {
        $this->stylists[] = new Prefixed($prefix);
        return $this;
    }

    public function length(int $fixedLength): static
    {
        $this->stylists[] = new FixedLength($fixedLength);
        return $this;
    }

    public function styles(array $stylists): static
    {
        $this->stylists = $stylists;
        return $this;
    }

    public function getStylists(): array
    {
        return $this->stylists;
    }
}
