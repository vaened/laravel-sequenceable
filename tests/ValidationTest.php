<?php
/**
 * Created by enea dhack - 24/06/17 09:52 PM
 */

namespace Enea\Tests;

use Enea\Sequenceable\Helper;

class ValidationTest extends TestCase
{

    function test_is_available_sequence()
    {
        // valid sequences
        $this->assertSame(Helper::getKeyName(0, 'column' ), 'column');
        $this->assertSame(Helper::getKeyName('column', 9 ), 'column');
        $this->assertSame(Helper::getKeyName('a', 'column' ), 'a');
        $this->assertSame(Helper::getKeyName('b', [ 'column' ] ), 'b');
        $this->assertSame(Helper::getKeyName('c', [ 'column' => 9] ), 'c');

        $this->assertSame(Helper::getColumnName(0, 'column' ), 'column');
        $this->assertSame(Helper::getColumnName('column', 9 ), 'column');
        $this->assertSame(Helper::getColumnName('a', 'column' ), 'column');
        $this->assertSame(Helper::getColumnName('b', [ 'column' ] ), 'column');
        $this->assertSame(Helper::getColumnName('c', [ 'column' => 9 ] ), 'column');

        $this->assertSame(Helper::getSize(0, 'column'), 0);
        $this->assertSame(Helper::getSize('column', 9), 9);
        $this->assertSame(Helper::getSize('a', 'column'), 0);
        $this->assertSame(Helper::getSize('b', ['column'] ), 0);
        $this->assertSame(Helper::getSize('c', ['column' => 9]), 9);

        $this->assertTrue(Helper::isAvailableSequence(0, 'column' ));
        $this->assertTrue(Helper::isAvailableSequence('column', 9 ));
        $this->assertTrue(Helper::isAvailableSequence('a', 'column' ));
        $this->assertTrue(Helper::isAvailableSequence('b', [ 'column' ] ));
        $this->assertTrue(Helper::isAvailableSequence('c', [ 'column' => 9 ] ));

        // invalid sequences
        $this->assertFalse(Helper::isAvailableSequence('a', []));
        $this->assertFalse(Helper::isAvailableSequence('b', ['column' => 'column']));
        $this->assertFalse(Helper::isAvailableSequence('c', ['column' => ['column']]));
        $this->assertFalse(Helper::isAvailableSequence(0, ['d' =>  'column']));
        $this->assertFalse(Helper::isAvailableSequence(0, null));
        $this->assertFalse(Helper::isAvailableSequence(1, 1));
        $this->assertFalse(Helper::isAvailableSequence(null, 1));
        $this->assertFalse(Helper::isAvailableSequence(1, []));
    }


}