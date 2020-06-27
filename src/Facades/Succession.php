<?php
/**
 * Created by enea dhack - 24/06/17 01:58 PM.
 */

namespace Enea\Sequenceable\Facades;

use Enea\Sequenceable\SequenceCollection;
use Enea\Sequenceable\Succession as BaseSuccession;
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
 * @see \Enea\Sequenceable\Succession
 */
class Succession extends Facade
{
    protected static function getFacadeAccessor()
    {
        return BaseSuccession::class;
    }
}
