<?php
/**
 * Created by eneasdh-fs - 11/01/17 10:00 PM.
 */

namespace Vaened\Sequenceable\Contracts;

use Vaened\SequenceGenerator\Contracts\SequenceRepository;
use Vaened\SequenceGenerator\Contracts\SequenceValue;

interface SequenceContract extends SequenceValue, SequenceRepository
{
}
