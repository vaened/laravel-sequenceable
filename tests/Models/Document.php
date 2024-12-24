<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Vaened\Sequenceable\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Vaened\Sequenceable\Contracts\SequenceableContract;
use Vaened\Sequenceable\Sequenceable;
use Vaened\Sequenceable\Serie;
use Vaened\Sequenceable\Wrap;

class Document extends Model implements SequenceableContract
{
    use Sequenceable;

    protected     $fillable = ['number', 'number_string', 'type'];

    protected     $table    = 'documents';

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
        if (!empty($this->sequences)) {
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
