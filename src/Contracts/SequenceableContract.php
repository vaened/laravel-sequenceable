<?php
/**
 * Created eneasdh-fs
 * Date: 11/01/17
 * Time: 10:07 PM
 */

namespace Enea\Sequenceable\Contracts;

use Enea\Sequenceable\Exceptions\SequenceException;


interface SequenceableContract
{

    /**
     * Gets the sequence model
     *
     * @return SequenceContract
     * @throws SequenceException
     */
    public function getSequenceModel( );

    /**
     * Sequence settings by model
     * @return array
     */
    public function getSequenceBindings();

}