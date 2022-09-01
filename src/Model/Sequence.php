<?php
/**
 * Created by eneasdh-fs - 11/12/16 09:35 PM.
 */

namespace Enea\Sequenceable\Model;

use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Vaened\SequenceGenerator\Contracts\SequenceValue;
use Vaened\SequenceGenerator\Serie;
use function hash;

/**
 * Model Sequence.
 *
 * Attributes
 *
 * @property  int sequence
 * @property  string id
 * @property  string source
 * @property  string column_id
 * */
class Sequence extends Model implements SequenceContract
{
    /**
     * Codification adler32.
     *
     * @var string
     */
    const HASH = 'adler32';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sequences';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['next', 'prev', 'current'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'source', 'column_id', 'sequence'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sequence' => 'int'
    ];

    /**
     * Returns the previous sequence.
     *
     * @return int
     */
    public function getPrevAttribute(): int
    {
        $prev = $this->sequence;
        return --$prev;
    }

    /**
     * Returns the current sequence.
     *
     * @return int
     */
    public function getCurrentAttribute(): int
    {
        return $this->sequence;
    }

    /**
     * Returns the next sequence.
     *
     * @return int
     */
    public function getNextAttribute(): int
    {
        $next = $this->sequence;
        return ++$next;
    }

    /**
     * {@inheritdoc}
     */
    public function next(): int
    {
        return ++$this->sequence;
    }

    /**
     * {@inheritdoc}
     */
    public function prev(): int
    {
        return --$this->sequence;
    }

    /**
     * {@inheritdoc}
     * */
    public function current(): int
    {
        return $this->sequence;
    }

    public function getAllFrom(string $source): array
    {
        return static::query()->where('source', '=', $source)->get()->all();
    }

    public function getCurrentValue(string $source, Serie $serie): SequenceValue
    {
        return $this->findOrCreateSeSequenceModel($source, $serie);
    }

    public function incrementByOne(string $source, Serie $serie): SequenceValue
    {
        $model = $this->findOrCreateSeSequenceModel($source, $serie);
        $model->increment('sequence', 1);
        return $model;
    }

    public function setValue(string $source, Serie $serie, int $quantity): SequenceValue
    {
        $model = $this->getSequenceModelInstance($source, $serie);
        $model->setAttribute('sequence', $quantity);
        $model->save();

        return $model;
    }

    protected function findOrCreateSeSequenceModel(string $table, Serie $serie): static
    {
        $model = $this->getSequenceModelInstance($table, $serie);
        $model->save();
        return $model;
    }

    private function getSequenceModelInstance(string $source, Serie $serie): static
    {
        $qualifiedName = $serie->getQualifiedName();
        $sequenceID    = $this->createSequenceID($source, $qualifiedName);

        $model = static::query()->firstOrNew(['id' => $sequenceID], [
            'source' => $source,
            'column_id' => $qualifiedName,
            'created_at' => Carbon::now(),
        ]);

        return $model;
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getQualifiedName(): string
    {
        return $this->column_id;
    }

    protected function createSequenceID(string $table, string $qualifiedName): string
    {
        return hash(self::HASH, "$table.$qualifiedName", false);
    }
}
