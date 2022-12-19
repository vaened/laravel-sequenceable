<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Tests\Models;

use Enea\Sequenceable\Contracts\SequenceableContract;
use Enea\Sequenceable\Sequenceable;
use Enea\Sequenceable\Serie;
use Enea\Sequenceable\Wrap;
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
        if (! empty($this->sequences)) {
            return $this->sequences;
        }

        return $this->defaultSequences();
    }

    private function defaultSequences(): array
    {
        return [
            Serie::lineal('number')->scope('document'),
            Wrap::create(CustomSequence::class, fn(Wrap $wrap) => $wrap->column('number_string')),
        ];
    }
}
