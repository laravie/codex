<?php

namespace Laravie\Codex\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\Testing\Faker;
use Laravie\Codex\TestCase\Acme\Client;

class ClientTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_has_proper_signature()
    {
        $stub = Client::make('abc');

        $this->assertInstanceOf('Laravie\Codex\Client', $stub);
        $this->assertSame('v1', $stub->getApiVersion());
        $this->assertSame('https://acme.laravie/', $stub->getApiEndpoint());
        $this->assertSame([], $stub->queries());
    }

    /** @test */
    public function it_can_set_custom_endpoint()
    {
        $stub = Client::make('abc');

        $this->assertSame('https://acme.laravie/', $stub->getApiEndpoint());

        $stub->useCustomApiEndpoint('https://beta.acme.laravie/');

        $this->assertSame('https://beta.acme.laravie/', $stub->getApiEndpoint());
    }

    /** @test */
    public function it_can_send_api_request_on_version_one()
    {
        $faker = Faker::create()
                    ->send('GET', [])
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                            ->uses('Welcome')
                            ->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /** @test */
    public function it_can_send_api_request_on_version_two()
    {
        $faker = Faker::create()
                    ->send('GET', ['Authorization' => 'Bearer abc'])
                    ->expectEndpointIs('https://acme.laravie/v2/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                            ->useVersion('v2')
                            ->uses('Welcome')
                            ->show();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /** @test */
    public function it_can_send_api_request_by_sending_array_data()
    {
        $payload = [
            'username' => 'homestead',
            'password' => 'secret',
        ];

        $faker = Faker::create()
                    ->send('POST', [], http_build_query($payload, null, '&'))
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                        ->uses('Welcome')
                        ->ping($payload);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /** @test */
    public function it_can_send_api_request_by_streaming()
    {
        $faker = Faker::create()
                    ->stream('POST', ['Accept' => 'application/json'])
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                            ->uses('Welcome')
                            ->streamPing([], ['Accept' => 'application/json']);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /** @test */
    public function it_can_send_api_request_by_sending_json_data_as_string()
    {
        $payload = ['meta' => ['foo', 'bar']];

        $faker = Faker::create()
                    ->sendJson('POST', [], json_encode($payload))
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                        ->uses('Welcome')
                        ->jsonPing($payload, []);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /** @test */
    public function it_can_send_api_request_by_sending_json_data_as_array()
    {
        $payload = ['meta' => ['foo', 'bar']];

        $faker = Faker::create()
                    ->sendJson('POST', [], $payload)
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                        ->uses('Welcome')
                        ->jsonPing($payload, []);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /** @test */
    public function it_can_send_api_request_by_providing_endpoint()
    {
        $faker = Faker::create()
                    ->call('GET', [], '')
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $response = (new Client($faker->http(), 'abc'))
                        ->uses('Welcome')
                        ->pong();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());
        $this->assertTrue($response->hasSanitizer());
    }

    /**
     * @test
     * @expectedException \Laravie\Codex\Exceptions\UnauthorizedException
     * @expectedExceptionMessage Not Authorized!
     */
    public function it_throws_exception_when_401_is_returned()
    {
        $faker = Faker::create()
                    ->call('GET', [], '')
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(401)
                    ->expectReasonPhraseIs('Not Authorized!');

        $response = (new Client($faker->http(), 'abc'))
                        ->uses('Welcome')
                        ->pong();
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage API version [v10] is not supported.
     */
    public function it_cant_use_unsupported_version_should_throw_exception()
    {
        Client::make('abc')->useVersion('v10');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Resource [Foobar] for version [v1] is not available.
     */
    public function it_cant_find_unknown_resource()
    {
        $http = m::mock('Http\Client\Common\HttpMethodsClient');

        $response = (new Client($http, 'abc'))->uses('Foobar');
    }
}
