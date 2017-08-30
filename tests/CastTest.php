<?php

namespace Laravie\Codex\TestCase;

use PHPUnit\Framework\TestCase;
use Laravie\Codex\TestCase\Acme\ArrayCast;

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


