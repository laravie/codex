<?php

namespace Laravie\Codex\TestCase;

use PHPUnit\Framework\TestCase;
use Laravie\Codex\TestCase\Acme\Client;

class ClientTest extends TestCase
{
    /** @test */
    function it_has_proper_signature()
    {
        $stub = new Client();

        $this->assertInstanceOf('Laravie\Codex\Client', $stub);
        $this->assertSame('v1', $stub->getApiVersion());
        $this->assertSame('https://acme.laravie/', $stub->getApiEndpoint());
    }
}
