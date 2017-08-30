<?php

namespace Laravie\Codex\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\TestCase\Acme\Client;

class ClientTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
    {
        m::close();
    }

    /** @test */
    function it_has_proper_signature()
    {
        $stub = Client::make('abc');

        $this->assertInstanceOf('Laravie\Codex\Client', $stub);
        $this->assertSame('v1', $stub->getApiVersion());
        $this->assertSame('https://acme.laravie/', $stub->getApiEndpoint());
    }

    /** @test */
    function it_can_send_api_request()
    {
        $http = m::mock('Http\Client\Common\HttpMethodsClient');
        $message = m::mock('Psr\Http\Message\ResponseInterface');

        $http->shouldReceive('send')->once()
            ->with('GET', m::type('GuzzleHttp\Psr7\Uri'), ['Authorization' => 'Bearer abc'], '')
            ->andReturn($message);

        $message->shouldReceive('getStatusCode')->andReturn(200)
            ->shouldReceive('getBody')->andReturn('{"success":true}');

        $response = (new Client($http, 'abc'))->resource('Welcome')->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage API version [v10] is not supported.
     */
    function it_cant_use_unsupported_version_should_throw_exception()
    {
        Client::make('abc')->useVersion('v10');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Resource [Foobar] for version [v1] is not available.
     */
    function it_cant_find_unknown_resource()
    {
        $http = m::mock('Http\Client\Common\HttpMethodsClient');

        $response = (new Client($http, 'abc'))->resource('Foobar');
    }
}
