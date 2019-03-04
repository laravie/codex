<?php

namespace Laravie\Codex\Testing;

use Mockery as m;
use GuzzleHttp\Psr7\Uri;
use Http\Client\HttpClient;
use Http\Message\RequestFactory;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Http\Client\Common\HttpMethodsClient;

class Faker
{
    /**
     * HTTP methods client.
     *
     * @var \Http\Client\Common\HttpMethodsClient
     */
    protected $http;

    /**
     * Mock for "Http\Client\HttpClient".
     *
     * @var \Mockery\MockeryInterface
     */
    protected $client;

    /**
     * Mock for "Http\Message\RequestFactory".
     *
     * @var \Mockery\MockeryInterface
     */
    protected $request;

    /**
     * Mock for "Psr\Http\Message\ResponseInterface".
     *
     * @var \Mockery\MockeryInterface
     */
    protected $message;

    /**
     * Expected URL endpoint.
     *
     * @var string
     */
    protected $expectedEndpoint;

    /**
     * Expected HTTP status code.
     *
     * @var array
     */
    protected $expectedHeaders = [];

    /**
     * Expected HTTP status code.
     *
     * @var int|null
     */
    protected $expectedStatusCode;

    /**
     * Expected Reason Phrase.
     *
     * @var string|null
     */
    protected $expectedReasonPhrase;

    /**
     * Expected HTTP body.
     *
     * @var string|null
     */
    protected $expectedBody;

    /**
     * Construct a fake request.
     */
    public function __construct()
    {
        $this->client = m::mock(HttpClient::class);
        $this->request = m::mock(RequestFactory::class);
        $this->message = m::mock(ResponseInterface::class);

        $this->http = new HttpMethodsClient(
            $this->client, $this->request
        );
    }

    /**
     * Create a fake request.
     *
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * Set expected URL.
     *
     * @param  string  $endpoint
     *
     * @return $this
     */
    public function expectEndpointIs(string $endpoint)
    {
        $this->expectedEndpoint = $endpoint;

        return $this;
    }

    /**
     * Make expected HTTP request.
     *
     * @param  string $method
     * @param  \Mockery\Matcher\Type|array  $headers
     * @param  \Mockery\Matcher\Type|mixed  $body
     *
     * @return $this
     */
    public function call(string $method, $headers = [], $body = '')
    {
        if ($method === 'GET') {
            $body = m::any();
        }

        $request = m::mock(RequestInterface::class);

        $this->request->shouldReceive('createRequest')
            ->with($method, m::type(Uri::class), $headers, $body)
            ->andReturnUsing(function ($m, $u, $h, $b) use ($request) {
                Assert::assertSame((string) $u, $this->expectedEndpoint);

                if (! empty($this->expectedHeaders)) {
                    Assert::assertArraySubset($this->expectedHeaders, $h);
                }

                return $request;
            });

        $this->client->shouldReceive('sendRequest')->with($request)->andReturn($this->message());

        return $this;
    }

    /**
     * Make expected HTTP request.
     *
     * @param  string $method
     * @param  \Mockery\Matcher\Type|array  $headers
     * @param  \Mockery\Matcher\Type|mixed  $body
     *
     * @return $this
     */
    public function send(string $method, $headers = [], $body = '')
    {
        return $this->call($method, $headers, $body);
    }

    /**
     * Make expected HTTP JSON request.
     *
     * @param  string $method
     * @param  \Mockery\Matcher\Type|array  $headers
     * @param  \Mockery\Matcher\Type|array|string  $body
     *
     * @return $this
     */
    public function sendJson(string $method, $headers = [], $body = '')
    {
        if (is_array($headers)) {
            $headers['Content-Type'] = 'application/json';
            $this->expectedHeaders = $headers;
        }

        if (is_array($body)) {
            $body = json_encode($body);
        }

        return $this->call($method, $headers, $body);
    }

    /**
     * Make expected HTTP JSON request.
     *
     * @param  string $method
     * @param  \Mockery\Matcher\Type|array  $headers
     *
     * @return $this
     */
    public function stream(string $method, $headers = [])
    {
        if (is_array($headers)) {
            $this->expectedHeaders = $headers;
        }

        $body = m::type(StreamInterface::class);

        return $this->call($method, m::type('Array'), $body);
    }

    /**
     * Request should response with.
     *
     * @param  int  $code
     * @param  string  $body
     *
     * @return $this
     */
    public function shouldResponseWith(int $code = 200, string $body = '')
    {
        $this->expectedStatusCode = $code;
        $this->expectedBody = $body;

        $this->message->shouldReceive('getStatusCode')->andReturn($code)
            ->shouldReceive('getBody')->andReturn($body);

        return $this;
    }

    /**
     * Response should have reason phrase as.
     *
     * @param  string  $reason
     *
     * @return $this
     */
    public function expectReasonPhraseIs(string $reason)
    {
        $this->expectedReasonPhrase = $reason;

        $this->message->shouldReceive('getReasonPhrase')->andReturn($reason);

        return $this;
    }

    /**
     * Get HTTP mock.
     *
     * @return \Http\Client\Common\HttpMethodsClient
     */
    public function http()
    {
        return $this->http;
    }

    /**
     * Get message mock.
     *
     * @return \Mockery\MockeryInterface
     */
    public function message()
    {
        return $this->message;
    }
}
