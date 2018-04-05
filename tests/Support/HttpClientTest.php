<?php

namespace Laravie\Codex\TestCase\Support;

use Mockery as m;
use GuzzleHttp\Psr7\Uri;
use Laravie\Codex\Request;
use Laravie\Codex\Endpoint;
use Laravie\Codex\Response;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\Support\HttpClient;
use Psr\Http\Message\StreamInterface;
use Laravie\Codex\Testing\FakeRequest;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Contracts\Response as ResponseContract;

class HttpClientTest extends TestCase
{
    use HttpClient;

    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();

        unset($this->http);
    }

    /** @test */
    public function it_can_send_http_request()
    {
        $headers = ['Accept' => 'application/json'];
        $payloads = ['search' => 'codex'];

        $faker = FakeRequest::create()
                        ->call('GET', $headers, '')
                        ->expectEndpointIs('https://laravel.com/docs/5.5?search=codex')
                        ->shouldResponseWith(200, '{"status":"success"}');

        $this->http = $faker->http();

        $response = $this->send('GET', (new Endpoint('https://laravel.com', ['docs', '5.5']))->get(), $headers, $payloads);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', $response->toArray()['status']);
    }

    /** @test */
    public function it_can_stream_http_request()
    {
        $headers = ['Content-Type' => 'application/json'];
        $payloads = ['search' => 'codex'];

        $stream = m::mock(StreamInterface::class);

        $faker = FakeRequest::create()
                        ->call('POST', $headers, $stream)
                        ->expectEndpointIs('https://laravel.com/codex/')
                        ->shouldResponseWith(200, '{"status":"success"}');

        $this->http = $faker->http();

        $response = $this->stream('POST', (new Endpoint('https://laravel.com/codex'))->get(), $headers, $stream);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', $response->toArray()['status']);
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
