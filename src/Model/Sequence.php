<?php
/**
 * Created by eneasdh-fs - 11/12/16 09:35 PM.
 */

namespace Enea\Sequenceable\Model;

use Enea\Sequenceable\Contracts\SequenceContract;
use Enea\Sequenceable\Serie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Model Sequence.
 *
 * Attributes
 *
 * @property  int sequence
 * @property  string id
 * @property  string source
 * @property  string description
 * @property  string column_key
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
    protected $fillable = ['id', 'source', 'column_key', 'sequence', 'description'];

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

    /**
     * {@inheritdoc}
     * */
    public function getColumnKey(): string
    {
        return $this->column_key;
    }

    /**
     * Returns the name of the field that stores the table to which the sequence belongs.
     *
     * @return string
     * */
    public function sourceTableName(): string
    {
        return 'source';
    }

    /**
     * {@inheritdoc}
     */
    public function getSeriesFrom(string $table): Collection
    {
        return static::where($this->sourceTableName(), $table)->get();
    }

    public function locateSerieModel(string $table, Serie $serie): SequenceContract
    {
        $columnID = $serie->getColumnKeyName();
        $serieID = $this->createSerieID($table, $columnID);
        return static::firstOrCreate(['id' => $serieID], $this->structure($table, $columnID));
    }

    private function structure(string $table, string $columnKeyName): array
    {
        return [
            $this->sourceTableName() => $table,
            'column_key' => $columnKeyName,
            'description' => "$table.$columnKeyName",
            'sequence' => 0
        ];
    }

    public function apply(): void
    {
        $this->save();
    }

    protected function createSerieID(string $table, string $getRealColumnName): string
    {
        return hash(self::HASH, "{$table}.{$getRealColumnName}", false);
    }
}
