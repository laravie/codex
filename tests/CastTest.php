<?php

namespace Laravie\Codex\TestCase;

use Laravie\Codex\Cast;
use PHPUnit\Framework\TestCase;

class CastTest extends TestCase
{
    /** @test */
    function it_can_cast_from_input()
    {
        $this->assertSame('["A","B","C"]', (new ArrayCast())->from(['A', 'B', 'C']));
    }

    /** @test */
    function it_can_cast_from_response()
    {
        $this->assertSame(['A', 'B', 'C'], (new ArrayCast())->to('["A","B","C"]'));
    }
}

class ArrayCast extends Cast
{
    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function isValid($value)
    {
        return is_array($value);
    }

    /**
     * Cast value from object.
     *
     * @param  object  $value
     *
     * @return mixed
     */
    protected function fromCast($value)
    {
        return json_encode($value);
    }

    /**
     * Cast value to object.
     *
     * @param  object  $value
     *
     * @return mixed
     */
    protected function toCast($value)
    {
        return json_decode($value);
    }
}
