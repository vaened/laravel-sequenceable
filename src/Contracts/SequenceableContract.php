<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Sequenceable\Contracts;

use Illuminate\Support\Collection;

interface SequenceableContract
{
    public function sequencesSetup(): array;

    public function getGroupedSequences(): Collection;
}
