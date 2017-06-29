<?php
/**
 * Created by enea dhack - 24/06/17 01:58 PM
 */

namespace Enea\Sequenceable\Facades;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Enea\Sequenceable\Succession as BaseSuccession;


/**
 * Class Succession
 * @package Enea\Sequenceable\Facade
 * @author enea dhack <enea.so@live.com>
 *
 * Methods
 *
 * @method static Collection on( string|int $class )
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