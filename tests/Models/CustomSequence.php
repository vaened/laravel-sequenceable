<?php
/**
 * Created by enea dhack - 24/06/17 11:33 PM.
 */

namespace Enea\Tests\Models;

use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Vaened\SequenceGenerator\Contracts\SequenceValue;
use Vaened\SequenceGenerator\Serie as BaseSerie;

/**
 * Model Sequence.
 *
 * Attributes
 *
 * @property  string source
 * @property  int sequence
 * */
class CustomSequence extends Model implements SequenceContract
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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

    public function getAllFrom(string $source): iterable
    {
        return $this->getSeriesFrom($source)->all();
    }

    public function getCurrentValue(string $source, BaseSerie $serie): SequenceValue
    {
        return $this->locateSerieModel($source, $serie);
    }

    public function incrementByOne(string $source, BaseSerie $serie): SequenceValue
    {
        return DB::transaction(function () use ($source, $serie) {
            $model = $this->locateSerieModel($source, $serie);
            $model->increment('sequence');
            return $model;
        });
    }

    public function setValue(string $source, BaseSerie $serie, int $quantity): SequenceValue
    {
        return DB::transaction(function () use ($source, $serie, $quantity) {
            return static::query()->lockForUpdate()->updateOrCreate([
                'source'    => $source,
                'column_id' => $serie->getSerieName()
            ], [
                'sequence' => $quantity,
            ]);
        });
    }

    public function getSource(): string
    {
        return $this->source;
    }

    public function getQualifiedName(): string
    {
        return $this->getAttributeValue('column_id');
    }

    protected function locateSerieModel(string $table, BaseSerie $serie): static
    {
        return static::query()->lockForUpdate()->firstOrCreate([
            'source'    => $table,
            'column_id' => $serie->getQualifiedName()
        ]);
    }
}
