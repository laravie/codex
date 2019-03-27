<?php

namespace Laravie\Codex\Tests;

use GuzzleHttp\Psr7\Uri;
use Laravie\Codex\Endpoint;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class EndpointTest extends TestCase
{
    /** @test */
    public function it_can_build_basic_endpoint()
    {
        $endpoint = new Endpoint('https://laravel.com', 'docs');

        $this->assertSame('https', $endpoint->getScheme());
        $this->assertSame('laravel.com', $endpoint->getHost());
        $this->assertSame('https://laravel.com', $endpoint->getUri());
        $this->assertSame(['docs'], $endpoint->getPath());
        $this->assertSame([], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('https://laravel.com/docs', (string) $endpoint->get());
        $this->assertSame('https://laravel.com/docs', (string) $endpoint);
    }

    /** @test */
    public function it_can_build_basic_endpoint_with_tailing_slash()
    {
        $endpoint = new Endpoint('https://laravel.com/', 'docs');

        $this->assertSame('https', $endpoint->getScheme());
        $this->assertSame('laravel.com', $endpoint->getHost());
        $this->assertSame('https://laravel.com', $endpoint->getUri());
        $this->assertSame(['docs'], $endpoint->getPath());
        $this->assertSame([], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('https://laravel.com/docs', (string) $endpoint->get());
        $this->assertSame('https://laravel.com/docs', (string) $endpoint);
    }

    /** @test */
    public function it_can_build_endpoint_with_multiple_paths()
    {
        $endpoint = new Endpoint('https://laravel.com', ['docs', '5.4']);

        $this->assertSame('https', $endpoint->getScheme());
        $this->assertSame('laravel.com', $endpoint->getHost());
        $this->assertSame('https://laravel.com', $endpoint->getUri());
        $this->assertSame(['docs', '5.4'], $endpoint->getPath());
        $this->assertSame([], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('https://laravel.com/docs/5.4', (string) $endpoint->get());
        $this->assertSame('https://laravel.com/docs/5.4', (string) $endpoint);
    }

    /** @test */
    public function it_can_build_basic_endpoint_with_query_string()
    {
        $endpoint = new Endpoint('https://laravel.com', 'docs', ['search' => 'controller']);

        $this->assertSame('https', $endpoint->getScheme());
        $this->assertSame('laravel.com', $endpoint->getHost());
        $this->assertSame('https://laravel.com', $endpoint->getUri());
        $this->assertSame(['docs'], $endpoint->getPath());
        $this->assertSame(['search' => 'controller'], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('https://laravel.com/docs?search=controller', (string) $endpoint->get());
        $this->assertSame('https://laravel.com/docs?search=controller', (string) $endpoint);
    }

    /** @test */
    public function it_can_build_basic_endpoint_appended_query_string()
    {
        $uri = new Uri('https://laravel.com/docs?search=controller&page=3');
        $endpoint = new Endpoint($uri);

        $this->assertSame('https', $endpoint->getScheme());
        $this->assertSame('laravel.com', $endpoint->getHost());
        $this->assertSame('https://laravel.com', $endpoint->getUri());
        $this->assertSame(['docs'], $endpoint->getPath());
        $this->assertSame(['search' => 'controller', 'page' => '3'], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('https://laravel.com/docs?search=controller&page=3', (string) $endpoint->get());
        $this->assertSame('https://laravel.com/docs?search=controller&page=3', (string) $endpoint);
    }

    /** @test */
    public function it_can_build_basic_endpoint_from_uri_instance()
    {
        $endpoint = (new Endpoint('https://laravel.com', 'docs'))
                        ->addQuery(['search' => 'controller', 'page' => 3]);

        $this->assertSame('https', $endpoint->getScheme());
        $this->assertSame('laravel.com', $endpoint->getHost());
        $this->assertSame('https://laravel.com', $endpoint->getUri());
        $this->assertSame(['docs'], $endpoint->getPath());
        $this->assertSame(['search' => 'controller', 'page' => '3'], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('https://laravel.com/docs?search=controller&page=3', (string) $endpoint->get());
        $this->assertSame('https://laravel.com/docs?search=controller&page=3', (string) $endpoint);
    }

    /** @test */
    public function it_can_set_endpoint_with_no_uri()
    {
        $endpoint = new Endpoint(null, 'docs');

        $this->assertSame('', $endpoint->getScheme());
        $this->assertSame('', $endpoint->getHost());
        $this->assertSame(null, $endpoint->getUri());
        $this->assertSame(['docs'], $endpoint->getPath());
        $this->assertSame([], $endpoint->getQuery());

        $this->assertInstanceOf(UriInterface::class, $endpoint->get());
        $this->assertSame('/docs', (string) $endpoint->get());
        $this->assertSame('/docs', (string) $endpoint);
    }

    /** @test */
    public function it_can_build_basic_endpoint_and_passthrough_with_input()
    {
        $endpoint = new Endpoint('https://laravel.com/docs');

        $this->assertSame('https', $endpoint->getScheme());

        $this->assertInstanceOf(Endpoint::class, $endpoint->withScheme('http'));

        $this->assertSame('http', $endpoint->getScheme());


        $this->assertInstanceOf(Endpoint::class, $endpoint->withUserInfo('laravie'));

        $this->assertSame('laravie', $endpoint->getUserInfo());
        $this->assertSame('http://laravie@laravel.com/docs', (string) $endpoint);
    }

    /** @test */
    public function it_cant_call_unknown_method_should_throw_exception()
    {
        $this->expectException('BadMethodCallException');
        $this->expectExceptionMessage("Method [getFullUrl] doesn't exists.");

        (new Endpoint(null, 'docs'))->getFullUrl();
    }
}
