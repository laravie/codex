<?php

namespace Laravie\Codex\Tests;

use Laravie\Codex\Discovery;
use PHPUnit\Framework\TestCase;

class DiscoveryTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown(): void
    {
        Discovery::flush();
    }

    /** @test */
    public function it_can_set_client()
    {
        $http = Discovery::client();

        $this->assertInstanceOf(\Http\Client\HttpClient::class, $http);

        $this->assertSame($http, Discovery::client());
    }

    /** @test */
    public function it_can_refresh_client()
    {
        $http = Discovery::client();

        $this->assertNotSame($http, Discovery::refreshClient());
    }

    /** @test */
    public function it_can_override_client()
    {
        $http1 = Discovery::client();
        $http2 = Discovery::override($override = Discovery::make());

        $this->assertNotSame($http1, $http2);
        $this->assertSame($override, $http2);
    }
}
