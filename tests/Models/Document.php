<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM
 */

namespace Enea\Tests\Models;


use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Sequenceable;
use Illuminate\Database\Eloquent\Model;

abstract class Document extends Model implements SequenceableContract
{
    use Sequenceable {
        Sequenceable::__construct as private __sequenceableConstructor;
    }

    protected $fillable = ['number', 'number_string'];

    protected $table = 'documents';

    public function __construct(array $attributes = array())
    {
        parent::__construct($attributes);
        $this->__sequenceableConstructor( );
    }

}