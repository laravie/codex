<?php

namespace Laravie\Codex\TestCase;

use Mockery as m;
use PHPUnit\Framework\TestCase;
use Laravie\Codex\Contracts\Client;
use Laravie\Codex\TestCase\Acme\One\Welcome;
use Laravie\Codex\Contracts\Request as RequestContract;

class RequestTest extends TestCase
{
    /**
     * Teardown the test environment.
     */
    protected function tearDown()
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
        $this->assertTrue($stub->hasSanitizer());
    }
}
