<?php
/**
 * Created by enea dhack - 26/06/2020 15:07.
 */

namespace Enea\Sequenceable;

use JetBrains\PhpStorm\Deprecated;
use Vaened\SequenceGenerator\Serie as BaseSerie;
use Vaened\SequenceGenerator\Stylists\FixedLength;

final class Serie extends BaseSerie
{
    public static function lineal(string $column): self
    {
        return new self($column);
    }

    #[Deprecated]
    public function length(?int $fixedLength): self
    {
        $this->styles([new FixedLength($fixedLength)]);
        return $this;
    }
}
