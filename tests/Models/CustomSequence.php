<?php
/**
 * Created by enea dhack - 24/06/17 11:33 PM.
 */

namespace Enea\Tests\Models;

use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Vaened\SequenceGenerator\Contracts\SequenceValue;
use Vaened\SequenceGenerator\Serie as BaseSerie;

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
    public function getSeriesFrom(string $table): Collection
    {
        return static::query()->where('source', $table)->get();
    }

    protected function locateSerieModel(string $table, BaseSerie $serie): static
    {
        return static::query()->firstOrCreate([
            'source' => $table,
            'column_id' => $serie->getQualifiedName()
        ]);
    }

    public function getAllFrom(string $source): array
    {
        return $this->getSeriesFrom($source)->all();
    }

    public function getCurrentValue(string $source, BaseSerie $serie): SequenceValue
    {
        return $this->locateSerieModel($source, $serie);
    }

    public function incrementByOne(string $source, BaseSerie $serie): SequenceValue
    {
        $model = $this->locateSerieModel($source, $serie);
        $model->increment('sequence');
        return $model;
    }

    public function setValue(string $source, BaseSerie $serie, int $quantity): SequenceValue
    {
        $model = static::query()->updateOrCreate([
            'source' => $source,
            'column_id' => $serie->getSerieName()
        ], [
            'sequence' => $quantity,
        ]);

        return $model;
    }

    public function getSource(): string
    {
        return $this->getSourceValue();
    }

    public function getQualifiedName(): string
    {
        return $this->getAttributeValue('column_id');
    }
}
