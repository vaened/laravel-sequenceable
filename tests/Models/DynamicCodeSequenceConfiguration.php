<?php
/**
 * Created by enea dhack - 25/06/17 02:32 PM
 */

namespace Enea\Tests\Models;


class DynamicCodeSequenceConfiguration extends Document
{
    public function sequencesSetup()
    {
        return [
            $this->getType( ) =>  'number',
        ];
    }

    public function getType( )
    {
        switch ($this->type){
            case 'tk': return 'ticket';
            case 'iv': return 'invoice';
        }
    }
}