<?php
/**
 * Created by enea dhack - 24/06/17 01:58 PM.
 */

namespace Vaened\Sequenceable\Facades;

use Vaened\Sequenceable\SequenceCollection;
use Vaened\Sequenceable\Succession as BaseSuccession;
use Illuminate\Support\Facades\Facade;

/**
 * Class Succession.
 *
 * @author enea dhack <enea.so@live.com>
 *
 * Methods
 *
 * @method static SequenceCollection from(string $class)
 *
 * @see \Vaened\Sequenceable\Succession
 */
class Succession extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaseSuccession::class;
    }
}
