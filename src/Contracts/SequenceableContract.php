<?php
/**
 * Created by enea dhack - 17/06/17 10:16 PM.
 */

namespace Enea\Sequenceable\Contracts;

use Illuminate\Support\Collection;

interface SequenceableContract
{
    /**
     * Returns the configuration of the sequences.
     *
     * @return array
     */
    public function sequencesSetup();

    /**
     * Returns, only if defined, the custom instances.
     *
     * @return Collection
     * */
    public function getSequenceModels();

    /**
     * Returns the sequences defined in the model.
     *
     * @return Collection
     */
    public function getSequencesConfiguration();
}