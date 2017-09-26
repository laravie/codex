<?php

namespace Laravie\Codex\TestCase\Exceptions;

use Mockery as m;
use Laravie\Codex\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Exceptions\HttpException;

class HttpExceptionTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    public function it_implements_the_contracts()
    {
        $response = m::mock(Response::class);

        $response->shouldReceive('getReasonPhrase')->andReturn('Not Found')
            ->shouldReceive('getStatusCode')->andReturn(404);

        $stub = new HttpException($response);

        $this->assertInstanceOf('RuntimeException', $stub);
        $this->assertInstanceOf('Http\Client\Exception', $stub);
    }

    /** @test */
    public function it_require_proper_response_object()
    {
        $response1 = m::mock(Response::class);
        $response2 = m::mock(ResponseInterface::class);

        $response1->shouldReceive('getReasonPhrase')->andReturn('Not Authorized')
            ->shouldReceive('getStatusCode')->andReturn(401);
        $response2->shouldReceive('getReasonPhrase')->andReturn('Server error')
            ->shouldReceive('getStatusCode')->andReturn(500);

        $stub1 = new HttpException($response1);
        $stub2 = new HttpException($response2);

        $this->assertSame($response1, $stub1->getResponse());
        $this->assertSame(401, $stub1->getStatusCode());

        $this->assertSame($response2, $stub2->getResponse());
        $this->assertSame(500, $stub2->getStatusCode());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $response is not an acceptable response object!
     */
    public function it_should_throw_exception_with_invalid_response_object()
    {
        $stub = new HttpException(null);
    }
}
