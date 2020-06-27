<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Tests\Models;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Sequenceable;
use Illuminate\Database\Eloquent\Model;

class Document extends Model implements SequenceableContract
{
    use Sequenceable;

    protected $fillable = ['number', 'number_string', 'type'];

    protected $table = 'documents';

    private array $sequences;

    public function __construct(array $attributes = [], array $sequences = [])
    {
        parent::__construct($attributes);
        $this->sequences = $sequences;
    }

    public static function create(array $sequences, array $attributes = [])
    {
        return new static($attributes, $sequences);
    }

    public function sequencesSetup(): array
    {
        return $this->sequences;
    }
}
