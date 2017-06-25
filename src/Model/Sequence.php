<?php
/**
 * Created by eneasdh-fs
 * Date: 11/12/16
 * Time: 09:35 PM
 */

namespace Enea\Sequenceable\Model;


use Illuminate\Database\Eloquent\Model;
use Enea\Sequenceable\Contracts\SequenceContract;

/**
 * Model Sequence
 *
 * Attributes
 *
 * @property  string id
 * @property  integer sequence
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
    protected $fillable = [ 'id', 'source' ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
       'sequence' => 'integer'
    ];

    /**
     * Returns the previous sequence
     *
     * @return int
     */
    public function getPrevAttribute( ): int
    {
        return $this->sequence --;
    }

    /**
     * Returns the current sequence
     *
     * @return int
     */
    public function getCurrentAttribute( ): int
    {
        return $this->sequence;
    }

    /**
     * Returns the next sequence
     *
     * @return int
     */
    public function getNextAttribute( ): int
    {
        return $this->sequence ++;
    }

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
    {
     * Get the first record matching the attributes or create it.
     *
     * @param string|integer $key
     * @param string $table
     * @param string $column
     * @return SequenceContract
     */
    public function findOrCreate( $key, $table, $column ): SequenceContract
    {
        $table = "$table.$column.$key";
        return static::firstOrCreate([ 'id' => $this->keyFormatted( $table ) ], [ 'source' => $table, 'sequence' => 0 ]);
    }

    /**
     * Format for the primary key
     * In case you do not need to format, return the primary key of the parameter
     *
     * @param $key
     * @return string|integer
     */
    protected function keyFormatted( $key )
    {
        return hash(self::HASH, $key, false);
    }

}