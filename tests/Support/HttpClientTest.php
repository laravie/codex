<?php

namespace Laravie\Codex\Tests\Support;

use Mockery as m;
use Laravie\Codex\Request;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\Testing\Faker;
use Laravie\Codex\Support\HttpClient;
use Psr\Http\Message\StreamInterface;

class HttpClientTest extends TestCase
{
    use HttpClient;

    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();

        unset($this->http);
    }

    /** @test */
    public function it_can_send_http_request()
    {
        $headers = ['Accept' => 'application/json'];
        $payloads = ['search' => 'codex'];

        $faker = Faker::create()
                        ->call('GET', $headers, '')
                        ->expectEndpointIs('https://laravel.com/docs/5.5?search=codex')
                        ->shouldResponseWith(200, '{"status":"success"}');

        $this->http = $faker->http();

        $response = $this->send('GET', Request::to('https://laravel.com', ['docs', '5.5']), $headers, $payloads);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', json_decode($response->getBody(), true)['status']);
    }

    /** @test */
    public function it_can_stream_http_request()
    {
        $headers = ['Content-Type' => 'application/json'];
        $payloads = ['search' => 'codex'];

        $stream = m::mock(StreamInterface::class);

        $faker = Faker::create()
                        ->call('POST', $headers, $stream)
                        ->expectEndpointIs('https://laravel.com/codex')
                        ->shouldResponseWith(200, '{"status":"success"}');

        $this->http = $faker->http();

        $response = $this->stream('POST', Request::to('https://laravel.com/codex'), $headers, $stream);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('success', json_decode($response->getBody(), true)['status']);
    }

    /**
     * Prepare request headers.
     *
     * @param  array  $headers
     *
     * @return array
     */
    protected function prepareRequestHeaders(array $headers = []): array
    {
        return $headers;
    }
}
