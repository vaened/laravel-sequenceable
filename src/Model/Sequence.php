<?php
/**
 * Created by eneasdh-fs
 * Date: 11/12/16
 * Time: 09:35 PM
 */

namespace Enea\Sequenceable\Model;

use Illuminate\Database\Eloquent\Model;
use Enea\Sequenceable\Contracts\SequenceContract;
use Illuminate\Support\Collection;

/**
 * Model Sequence
 *
 * Attributes
 *
 * @property  integer sequence
 *
 * @property  string id
 * @property  string source
 * @property  string description
 * @property  string column_key
 * */
class Sequence extends Model implements SequenceContract
{

    /**
     * Codification adler32
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
    protected $appends = [ 'next', 'prev', 'current' ];

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
    protected $fillable = [ 'id', 'source', 'column_key', 'description' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'sequence' => 'integer'
    ];

    /**
     * Returns the previous sequence.
     *
     * @return int
     */
    public function getPrevAttribute()
    {
        $prev = $this->sequence;
        $prev --;
        return $prev;
    }

    /**
     * Returns the current sequence.
     *
     * @return int
     */
    public function getCurrentAttribute()
    {
        return $this->sequence;
    }

    /**
     * Returns the next sequence.
     *
     * @return int
     */
    public function getNextAttribute()
    {
        $next = $this->sequence;
        $next ++;
        return $next;
    }

    /**
     * Increase sequence by one and return it.
     *
     * @return integer
     */
    public function next()
    {
        $this->sequence ++;
        $this->save( );
        return $this->sequence;
    }

    /**
     * Decrements the sequence by one and return it.
     *
     * @return integer
     */
    public function prev()
    {
        $this->sequence --;
        $this->save();
        return $this->sequence;
    }


    /**
     * Gets the current sequence.
     *
     * @return integer
     * */
    public function current()
    {
        return $this->sequence;
    }

    /**
     * Returns the field that stores the column to which the sequence belongs.
     *
     * @return string
     * */
    public function getColumnKey()
    {
        return $this->column_key;
    }

    /**
     * Returns the name of the field that stores the table to which the sequence belongs.
     *
     * @return string
     * */
    public function sourceTableName()
    {
        return 'source';
    }

    /**
     * Filters only the tables that are passed by parameter.
     *
     * @param string $table
     * @return Collection
     */
    public function source($table)
    {
        return static::where($this->sourceTableName(), $table)->get();
    }

    /**
     * Get the first record matching the attributes or create it.
     *
     * @param string|integer $key
     * @param string $table
     * @param string $column
     * @return SequenceContract
     */
    public function findOrCreate($key, $table, $column)
    {
        $column = $this->buildColumnKey($column, $key);

        return static::firstOrCreate([ 'id' => $this->keyFormatted($table, $column)], [
            $this->sourceTableName() => $table,
            'column_key' => $column,
            'description' => "$table.$column",
            'sequence' => 0
        ]);
    }

    /**
     * Format for the primary key.
     * In case you do not need to format, return the primary key of the parameter.
     *
     * @param string $table
     * @param string $column_key
     * @return string
     */
    protected function keyFormatted($table, $column_key)
    {
        return hash(self::HASH, "$table.$column_key", false);
    }

    /**
     * Format the key of the column.
     *
     * @param string $column
     * @param string $key
     * @return string
     */
    protected function buildColumnKey($column,  $key)
    {
        if ($key !== $column) {
            $column .= '.' . $key;
        }

        return $column;
    }

}