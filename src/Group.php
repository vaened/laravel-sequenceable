<?php
/**
 * Created by enea dhack - 26/06/2020 20:50.
 */

namespace Enea\Sequenceable;

use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Support\Collection;

class Group
{
    private SequenceContract $sequence;

    private Collection $series;

    public function __construct(SequenceContract $sequence, Collection $series)
    {
        $this->sequence = $sequence;
        $this->series = $series;
    }

    public function sequence(): SequenceContract
    {
        return $this->sequence;
    }

    public function series(): Collection
    {
        return $this->series;
    }
}
