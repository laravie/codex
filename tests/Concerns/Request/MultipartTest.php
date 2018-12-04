<?php

namespace Laravie\Codex\TestCase\Concerns\Request;

use Mockery as m;
use Laravie\Codex\Request;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\Contracts\Client;
use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\Contracts\Response;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Laravie\Codex\Concerns\Request\Multipart;

class MultipartTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_can_append_data_from_endpoint_when_sending_stream_request()
    {
        $message = m::mock(ResponseInterface::class);
        $message->shouldReceive('getStatusCode')->once()->andReturn(200);

        $client = m::mock(Client::class);
        $client->shouldReceive('getApiEndpoint')->once()->andReturn('https://laravel.com/')
            ->shouldReceive('stream')
                ->once()
                ->with('POST', m::type(Endpoint::class), m::type('Array'), m::type(StreamInterface::class))
                ->andReturn($message);

        $endpoint = m::mock(Endpoint::class);
        $endpoint->shouldReceive('getPath')->andReturn(['docs', '5.5'])
            ->shouldReceive('getQuery')->andReturn(['search' => 'codex']);

        $stub = new class() extends Request {
            use Multipart;

            public function ping($endpoint)
            {
                return $this->stream('POST', $endpoint, [], [], []);
            }
        };

        $this->assertSame(
            $message, $stub->setClient($client)->ping($endpoint)->message
        );
    }

    /** @test */
    public function it_doesnt_attach_multipart_when_sending_without_proper_header_and_files()
    {
        $message = m::mock(ResponseInterface::class);
        $message->shouldReceive('getStatusCode')->once()->andReturn(200);

        $client = m::mock(Client::class);
        $client->shouldReceive('getApiEndpoint')->once()->andReturn('https://laravel.com/')
            ->shouldReceive('send')
                ->once()
                ->with('POST', m::type(Endpoint::class), ['Content-Type' => 'application/json'], ['search' => 'codex'])
                ->andReturn($message);

        $stub = new class() extends Request {
            use Multipart;

            public function ping()
            {
                list($headers, $stream) = $this->prepareMultipartRequestPayloads(
                    ['Content-Type' => 'application/json'], ['search' => 'codex'], []
                );

                return $this->send('POST', 'docs/5.5', $headers, $stream);
            }
        };

        $this->assertSame(
            $message, $stub->setClient($client)->ping()->message
        );
    }

    /** @test */
    public function it_can_convert_body_contents_to_multipart()
    {
        $message = m::mock(ResponseInterface::class);
        $message->shouldReceive('getStatusCode')->once()->andReturn(200);

        $client = m::mock(Client::class);
        $client->shouldReceive('getApiEndpoint')->once()->andReturn('https://laravel.com/')
            ->shouldReceive('stream')
                ->once()
                ->with('POST', m::type(Endpoint::class), m::type('Array'), m::type(StreamInterface::class))
                ->andReturnUsing(function ($m, $u, $h, $b) use ($message) {
                    $content = $b->getContents();

                    $this->assertContains('Content-Disposition: form-data; name="search"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="project"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="developer[name]"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="developer[email]"', $content);

                    return $message;
                });

        $stub = new class() extends Request {
            use Multipart;

            public function ping()
            {
                $payload = [
                    'search' => 'codex',
                    'project' => 'laravie',
                    'developer' => [
                        'name' => 'Mior Muhammad Zaki',
                        'email' => 'crynobone@gmail.com',
                    ],
                ];

                return $this->stream('POST', 'docs/5.5', [], $payload, []);
            }
        };

        $this->assertSame(
            $message, $stub->setClient($client)->ping()->message
        );
    }

    /** @test */
    public function it_can_attach_files_for_multipart_uploads()
    {
        $message = m::mock(ResponseInterface::class);
        $message->shouldReceive('getStatusCode')->once()->andReturn(200);

        $client = m::mock(Client::class);
        $client->shouldReceive('getApiEndpoint')->once()->andReturn('https://laravel.com/')
            ->shouldReceive('stream')
                ->once()
                ->with('POST', m::type(Endpoint::class), m::type('Array'), m::type(StreamInterface::class))
                ->andReturnUsing(function ($m, $u, $h, $b) use ($message) {
                    $content = $b->getContents();

                    $this->assertContains('Content-Disposition: form-data; name="hello"; filename="hello.txt"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="logo"; filename="logo.png"', $content);

                    $this->assertContains('Content-Disposition: form-data; name="search"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="project"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="developer[name]"', $content);
                    $this->assertContains('Content-Disposition: form-data; name="developer[email]"', $content);

                    return $message;
                });

        $stub = new class() extends Request {
            use Multipart;

            public function ping()
            {
                $payload = [
                    'search' => 'codex',
                    'project' => 'laravie',
                    'developer' => [
                        'name' => 'Mior Muhammad Zaki',
                        'email' => 'crynobone@gmail.com',
                    ],
                ];

                $files = [
                    'hello' => __DIR__.'/../../stubs/hello.txt',
                    'logo' => __DIR__.'/../../stubs/logo.png',
                ];

                return $this->stream('POST', 'docs/5.5', [], $payload, $files);
            }
        };

        $this->assertSame(
            $message, $stub->setClient($client)->ping()->message
        );
    }
}
