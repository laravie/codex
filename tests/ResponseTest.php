<?php

namespace Laravie\Codex\TestCase;

use Mockery as m;
use Laravie\Codex\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class ResponseTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    function it_can_build_a_basic_response()
    {
        $data = ['name' => 'Laravie Codex'];

        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getBody')->once()->andReturn(json_encode($data));

        $stub = new Response($api);

        $this->assertEquals($data, $stub->toArray());
    }

    /** @test */
    function it_can_return_status_code()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->once()->andReturn(201);

        $stub = new Response($api);

        $this->assertEquals(201, $stub->getStatusCode());
    }
}
