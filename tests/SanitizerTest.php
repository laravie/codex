<?php

namespace Laravie\Codex\TestCase;

use Laravie\Codex\Sanitizer;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\TestCase\Acme\Casts\Arr;
use Laravie\Codex\TestCase\Acme\Casts\Carbon;

class SanitizerTest extends TestCase
{
    /** @test */
    function it_can_sanitize_from_inputs()
    {
        $stub = new Sanitizer();
        $stub->add('meta', new Arr());
        $stub->add('created_at', new Carbon());

        $given = [
            'meta' => ['circles' => [1, 2, 3], 'score' => 100],
            'created_at' => new \DateTime('2017-08-31'),
        ];

        $expected = [
            'meta' => '{"circles":[1,2,3],"score":100}',
            'created_at' => '2017-08-31',
        ];

        $this->assertEquals($expected, $stub->from($given));
    }

    /** @test */
    function it_can_sanitize_to_response()
    {
        $stub = new Sanitizer();
        $stub->add('meta', new Arr());
        $stub->add('created_at', new Carbon());

        $given = [
            'meta' => '{"circles":[1,2,3],"score":100}',
            'created_at' => '2017-08-31',
        ];

        $expected = [
            'meta' => ['circles' => [1, 2, 3], 'score' => 100],
            'created_at' => new \DateTime('2017-08-31'),
        ];

        $this->assertEquals($expected, $stub->to($given));
    }
}
