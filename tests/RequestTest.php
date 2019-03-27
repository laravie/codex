<?php

namespace Laravie\Codex\TestCase;

use Mockery as m;
use Laravie\Codex\Request;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\Testing\Faker;
use Laravie\Codex\Contracts\Client;
use Laravie\Codex\Contracts\Endpoint;
use Laravie\Codex\TestCase\Acme\One\Welcome;
use Laravie\Codex\Contracts\Request as RequestContract;

class RequestTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /** @test */
    public function it_define_http_methods_as_consts()
    {
        $this->assertSame('GET', RequestContract::METHOD_GET);
        $this->assertSame('POST', RequestContract::METHOD_POST);
        $this->assertSame('PATCH', RequestContract::METHOD_PATCH);
        $this->assertSame('PUT', RequestContract::METHOD_PUT);
        $this->assertSame('DELETE', RequestContract::METHOD_DELETE);
    }

    /** @test */
    public function it_has_proper_signature()
    {
        $client = m::mock(Client::class);

        $stub = new Welcome($client);

        $this->assertInstanceOf(RequestContract::class, $stub);
        $this->assertSame('v1', $stub->getVersion());
    }

    /** @test */
    public function it_can_proxy_request_via_different_version()
    {
        $faker = Faker::create()
                    ->send('GET', ['Authorization' => 'Bearer abc'])
                    ->expectEndpointIs('https://acme.laravie/v1/welcome')
                    ->shouldResponseWith(200, '{"success":true}');

        $welcome = (new Acme\Client($faker->http(), 'abc'))
                            ->useVersion('v2')
                            ->uses('Welcome');

        $response = $welcome->legacyShow();

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('{"success":true}', $response->getBody());
        $this->assertSame(['success' => true], $response->getContent());
        $this->assertSame(['success' => true], $response->toArray());

        $this->assertSame('v2', $welcome->getVersion());
    }

    /** @test */
    public function it_can_create_instance_of_endpoint()
    {
        $stub = Request::to('https://laravel.com/docs/5.4?search=controller');

        $this->assertInstanceOf(Endpoint::class, $stub);
        $this->assertSame('https://laravel.com', $stub->getUri());
        $this->assertSame(['docs', '5.4'], $stub->getPath());
        $this->assertSame(['search' => 'controller'], $stub->getQuery());
    }
}
