<?php

namespace Laravie\Codex\Tests;

use GuzzleHttp\Psr7\Utils;
use Laravie\Codex\Response;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ResponseTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_implements_proper_contract()
    {
        $message = m::mock(ResponseInterface::class);

        $stub = new Response($message);

        $this->assertInstanceOf('Laravie\Codex\Contracts\Response', $stub);
        $this->assertSame($message, $stub->message);
        $this->assertNull($stub->original);
    }

    /** @test */
    public function it_can_build_a_basic_response()
    {
        $json = '{"name":"Laravie Codex"}';
        $data = ['name' => 'Laravie Codex'];

        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getBody')->twice()->andReturn(Utils::streamFor($json));

        $stub = new Response($api);

        $this->assertSame($data, $stub->toArray());
        $this->assertSame($json, $stub->getBody());
    }

    /** @test */
    public function it_can_return_status_code()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->times(4)->andReturn(201);

        $stub = new Response($api);

        $this->assertSame(201, $stub->getStatusCode());
        $this->assertTrue($stub->isSuccessful());
        $this->assertFalse($stub->isUnauthorized());
        $this->assertFalse($stub->isNotFound());
    }

    /** @test */
    public function it_can_return_status_code_when_unauthorized()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->times(4)->andReturn(401);

        $stub = new Response($api);

        $this->assertSame(401, $stub->getStatusCode());
        $this->assertFalse($stub->isSuccessful());
        $this->assertTrue($stub->isUnauthorized());
        $this->assertFalse($stub->isNotFound());
    }

    /** @test */
    public function it_can_return_status_code_when_missing()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->times(4)->andReturn(404);

        $stub = new Response($api);

        $this->assertSame(404, $stub->getStatusCode());
        $this->assertFalse($stub->isSuccessful());
        $this->assertFalse($stub->isUnauthorized());
        $this->assertTrue($stub->isNotFound());
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
    public function it_can_get_body_from_stream()
    {
        $stream = m::mock(StreamInterface::class);
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getBody')->andReturn($stream);
        $stream->shouldReceive('__toString')->andReturn('Text from stream');

        $stub = new Response($api);

        $this->assertSame('Text from stream', $stub->getBody());
    }

    /** @test */
    public function it_would_throw_exception_when_given_401_status_code()
    {
        $this->expectException('Laravie\Codex\Exceptions\UnauthorizedException');
        $this->expectExceptionMessage('Not authorized');

        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(401)
            ->shouldReceive('getReasonPhrase')->andReturn('Not authorized');

        (new Response($api))->validate();
    }

    /** @test */
    public function it_can_use_then()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(200);

        $stub = new Response($api);

        $stub->then(function ($response, $code) use ($stub) {
            $this->assertSame($stub, $response);
            $this->assertSame(200, $code);
        });

        $this->assertInstanceOf(Response::class, $stub);
    }

    /** @test */
    public function it_can_use_validate_with()
    {
        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(200);

        $stub = (new Response($api))->validateWith(function ($code, $response) {
            $response->abortIfRequestHasFailed();
            $response->abortIfRequestNotFound();
        });

        $this->assertInstanceOf(Response::class, $stub);
    }

    /** @test */
    public function it_can_use_validate_with_can_throws_exception_request_has_failed()
    {
        $this->expectException('Laravie\Codex\Exceptions\HttpException');
        $this->expectExceptionMessage('404 File not found.');

        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(404);

        (new Response($api))->validateWith(function ($code, $response) {
            $response->abortIfRequestHasFailed('404 File not found.');
        });
    }

    /** @test */
    public function it_can_use_validate_with_can_throws_exception_request_not_found()
    {
        $this->expectException('Laravie\Codex\Exceptions\NotFoundException');
        $this->expectExceptionMessage('404 File not found.');

        $api = m::mock(ResponseInterface::class);

        $api->shouldReceive('getStatusCode')->andReturn(404);

        (new Response($api))->validateWith(function ($code, $response) {
            $response->abortIfRequestNotFound('404 File not found.');
        });
    }

    /** @test */
    public function it_cant_return_unknown_parent_methods_should_throw_exception()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage("Method [getRequest] doesn't exists.");

        (new Response(m::mock(ResponseInterface::class)))->getRequest();
    }
}
