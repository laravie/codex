<?php

namespace Laravie\Codex\TestCase;

use Mockery as m;
use Laravie\Codex\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Exceptions\HttpException;

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
    public function it_implements_proper_contract()
    {
        $stub = new Response(m::mock(ResponseInterface::class));

        $this->assertInstanceOf('Laravie\Codex\Contracts\Response', $stub);
    }

    /** @test */
    public function it_can_build_a_basic_response()
    {
        $json = '{"name":"Laravie Codex"}';
        $data = ['name' => 'Laravie Codex'];

        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getBody')->twice()->andReturn($json);

        $stub = new Response($api);

        $this->assertSame($data, $stub->toArray());
        $this->assertSame($json, $stub->getBody());
    }

    /** @test */
    public function it_can_return_status_code()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->once()->andReturn(201);

        $stub = new Response($api);

        $this->assertSame(201, $stub->getStatusCode());
    }

    /** @test */
    public function it_can_return_parent_methods()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getProtocolVersion')->andReturn('1.1');

        $stub = new Response($api);

        $this->assertSame('1.1', $stub->getProtocolVersion());
    }

    /** @test */
    public function it_can_use_validate_with()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(200);

        $stub = (new Response($api))->validateWith(function ($code, $response) {
            if ($code === 404) {
                throw new HttpException($response, '404 File not found.');
            }
        });

        $this->assertInstanceOf(Response::class, $stub);
    }

    /**
     * @test
     * @expectedException \Laravie\Codex\Exceptions\HttpException
     * @expectedExceptionMessage 404 File not found.
     */
    public function it_can_use_validate_with_can_throws_exception()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(404);

        (new Response($api))->validateWith(function ($code, $response) {
            if ($code === 404) {
                throw new HttpException($response, '404 File not found.');
            }
        });
    }

    /**
     * @test
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage Method [getRequest] doesn't exists.
     */
    public function it_cant_return_unknown_parent_methods_should_throw_exception()
    {
        (new Response(m::mock(ResponseInterface::class)))->getRequest();
    }
}
