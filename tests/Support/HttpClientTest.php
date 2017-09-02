<?php

namespace Laravie\Codex\TestCase\Support;

use Mockery as m;
use Laravie\Codex\Endpoint;
use Laravie\Codex\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;
use Laravie\Codex\Support\HttpClient;

class HttpClientTest extends TestCase
{
    use HttpClient;

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    function it_can_return_same_instance_when_given_contract()
    {
        $endpoint = m::mock(Endpoint::class);
        $stub = $this->convertUriToEndpoint($endpoint);

        $this->assertSame($endpoint, $stub);
    }

    /** @test */
    function it_can_return_endpoint_when_given_uri()
    {
        $endpoint = m::mock(UriInterface::class);

        $endpoint->shouldReceive('getQuery')->andReturn('foo=bar');

        $stub = $this->convertUriToEndpoint($endpoint);

        $this->assertInstanceOf(Endpoint::class, $stub);
        $this->assertSame(['foo' => 'bar'], $stub->getQuery());
    }

    /** @test */
    function it_can_return_endpoint_when_given_string()
    {
        $endpoint = m::mock(UriInterface::class);

        $stub = $this->convertUriToEndpoint('https://laravel.com/docs/5.4?search=controller');

        $this->assertInstanceOf(Endpoint::class, $stub);
        $this->assertSame('https://laravel.com', $stub->getUri());
        $this->assertSame(['docs', '5.4'], $stub->getPath());
        $this->assertSame(['search' => 'controller'], $stub->getQuery());
    }

    /**
     * Resolve the responder class.
     *
     * @param  \Psr\Http\Message\ResponseInterface  $response
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function responseWith(ResponseInterface $response)
    {
        return new Response($response);
    }

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    protected function prepareRequestHeaders(array $headers = [])
    {
        return $headers;
    }
}
