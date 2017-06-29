<?php
/**
 * Created by enea dhack - 24/06/17 11:33 PM
 */

namespace Enea\Tests\Models;


use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Model Sequence
 *
 * Attributes
 *
 * @property  string id
 * @property  integer sequence
 * */
class CustomSequence extends Model implements SequenceContract
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [ 'id', 'source', 'column_key', 'key'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sequence' => 'integer'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Increase sequence by one and return it
     *
     * @return integer
     */
    public function next( ): int
    {
        $this->sequence ++;
        $this->save( );
        return $this->sequence;
    }

    /**
     * Decrements the sequence by one and return it
     *
     * @return integer
     */
    public function prev( ): int
    {
        $this->sequence --;
        $this->save();
        return $this->sequence;
    }


    /**
     * Gets the current sequence
     *
     * @return integer
     * */
    public function current( ): int
    {
        return $this->sequence;
    }

    /**
     * Returns the field that stores the column to which the sequence belongs
     *
     * @return string
     * */
    public function getColumnKey(): string
    {
        return $this->column_key;
    }


    /**
     * Filters only the tables that are passed by parameter
     *
     * @param string $table
     * @return Collection
     */
    public function source(string $table): Collection
    {
        return static::where( 'source', $table )->get();
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param string|integer $key
     * @param string $table
     * @param string $column
     * @return SequenceContract
     */
    public function findOrCreate( $key, $table, $column ): SequenceContract
    {
        if ($key !== $column) {
            $column .= '.' . $key;
        }

        return static::firstOrCreate([ 'key' => $key ], [
            'source' => $table,
            'column_key' => $column,
            'sequence' => 0
        ]);
    }

}