<?php

namespace Laravie\Codex\TestCase;

use Laravie\Codex\Endpoint;
use PHPUnit\Framework\TestCase;

class EndpointTest extends TestCase
{
    /** @test */
    public function it_can_build_basic_endpoint()
    {
        $endpoint = new Endpoint('https://laravel.com', 'docs');

        $this->assertEquals('https://laravel.com/docs', (string) $endpoint->get());
    }

    /** @test */
    public function it_can_build_endpoint_with_multiple_paths()
    {
        $endpoint = new Endpoint('https://laravel.com', ['docs', '5.4']);

        $this->assertEquals('https://laravel.com/docs/5.4', (string) $endpoint->get());
    }

    /** @test */
    public function it_can_build_basic_endpoint_with_query_string()
    {
        $endpoint = new Endpoint('https://laravel.com', 'docs', ['search' => 'controller']);

        $this->assertEquals('https://laravel.com/docs?search=controller', (string) $endpoint->get());
    }

    /** @test */
    public function it_can_build_basic_endpoint_appended_query_string()
    {
        $endpoint = new Endpoint('https://laravel.com', 'docs');

        $endpoint->addQuery(['search' => 'controller', 'page' => 3]);

        $this->assertEquals('https://laravel.com/docs?search=controller&page=3', (string) $endpoint->get());
    }
}
