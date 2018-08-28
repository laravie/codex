<?php

namespace Laravie\Codex\TestCase;

use Laravie\Codex\Payload;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    /** @test */
    public function it_can_be_initiated()
    {
        $this->assertSame('foo', Payload::make('foo')->get());
        $this->assertSame('foo', (new Payload('foo'))->get());
    }

    /** @test */
    public function it_would_return_same_instance_if_already_a_payload()
    {
        $payload = Payload::make('foo');

        $this->assertSame($payload, Payload::make($payload));
    }

    /** @test */
    public function it_can_handle_json_content_type()
    {
        $payload = Payload::make(['hello' => 'world', 'foo' => ['bar']]);

        $this->assertSame('{"hello":"world","foo":["bar"]}', $payload->get(['Content-Type' => 'application/json']));
    }

    /** @test */
    public function it_can_handle_array_as_http_queries()
    {
        $payload = Payload::make(['hello' => 'world', 'foo' => ['bar']]);

        $this->assertSame('hello=world&foo%5B0%5D=bar', $payload->get());
    }
}
