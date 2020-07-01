<?php
/**
 * Created by enea dhack - 30/06/2020 18:11.
 */

namespace Enea\Sequenceable;

use LogicException;

class Builder
{
    public function build(Serie $serie, int $sequence)
    {
        $this->validateLength($sequence, $serie->getFixedLength());
        return $this->stylize($serie, $sequence);
    }

    private function stylize(Serie $serie, int $sequence): string
    {
        return $this->toFixedLength($sequence, $serie->getFixedLength());
    }

    private function toFixedLength(string $number, int $length): string
    {
        return str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    private function validateLength(string $sequence, int $maxLength): void
    {
        if ($maxLength > 0 && strlen($sequence) > $maxLength) {
            throw  new LogicException('the sequence has exceeded the size limit');
        }
    }
}
