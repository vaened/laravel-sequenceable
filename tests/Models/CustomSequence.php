<?php
/**
 * Created by enea dhack - 24/06/17 11:33 PM.
 */

namespace Enea\Tests\Models;

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
 * */
class CustomSequence extends Model implements SequenceContract
{
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
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
    public function getColumnID(): string
    {
        return $this->getAttributeValue('column_id');
    }

    /**
     * {@inheritdoc}
     * */
    public function getSeriesFrom(string $table): Collection
    {
        return static::query()->where('source', $table)->get();
    }

    /**
     * {@inheritdoc}
     * */
    public function getSourceValue(): string
    {
        return $this->getAttributeValue('source');
    }

    /**
     * {@inheritdoc}
     * */
    public function incrementOneTo(string $table, Serie $serie): int
    {
        $model = $this->locateSerieModel($table, $serie);
        $model->increment('sequence');
        return $model->getAttributeValue('sequence');
    }

    protected function locateSerieModel(string $table, Serie $serie): Model
    {
        return static::query()->firstOrCreate([
            'source' => $table,
            'column_id' => $serie->getColumnID()
        ]);
    }
}
