<?php

namespace Laravie\Codex\Tests;

use PHPUnit\Framework\TestCase;
use Laravie\Codex\Tests\Acme\Casts\Arr;

class CastTest extends TestCase
{
    /** @test */
    public function it_can_cast_from_input()
    {
        $this->assertSame('["A","B","C"]', (new Arr())->from(['A', 'B', 'C']));
    }

    /** @test */
    public function it_can_cast_from_response()
    {
        $this->assertSame(['A', 'B', 'C'], (new Arr())->to('["A","B","C"]'));
    }
}
