<?php

namespace Laravie\Codex\Tests\Acme\One;

use Laravie\Codex\Concerns\Request\Json;
use Laravie\Codex\Concerns\Request\Multipart;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;
use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Contracts\Response as ResponseContract;
use Laravie\Codex\Contracts\Sanitizer as SanitizerContract;
use Laravie\Codex\Endpoint;
use Laravie\Codex\Filter\Sanitizer;
use Laravie\Codex\Filter\WithSanitizer;
use Laravie\Codex\Request;
use Psr\Http\Message\ResponseInterface;

class Welcome extends Request implements Filterable
{
    use Json, Multipart, WithSanitizer;

    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v1';

    /**
     * Show welcome.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function show()
    {
        return $this->send('GET', 'welcome', $this->mergeApiHeaders([]), $this->mergeApiBody([]));
    }

    /**
     * Ping welcome.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function ping($body, array $headers = [])
    {
        return $this->send('POST', 'welcome', $this->mergeApiHeaders($headers), $body);
    }

    /**
     * Ping welcome.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function jsonPing($body, array $headers = [])
    {
        return $this->sendJson('POST', 'welcome', $this->mergeApiHeaders($headers), $body);
    }

    /**
     * Ping welcome.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function streamPing($body, array $headers = [])
    {
        return $this->stream('POST', 'welcome', $this->mergeApiHeaders($headers), $body);
    }

    /**
     * Pong welcome.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function pong()
    {
        return $this->send('GET', new Endpoint(null, 'welcome'));
    }

    /**
     * Get API Endpoint.
     *
     * @param  string|array  $path
     */
    protected function getApiEndpoint($path = []): EndpointContract
    {
        if (\is_array($path)) {
            array_unshift($path, $this->getVersion());
        } else {
            $path = [$this->getVersion(), $path];
        }

        return parent::getApiEndpoint($path);
    }

    /**
     * Resolve the responder class.
     */
    protected function responseWith(ResponseInterface $message): ResponseContract
    {
        return new Response($message);
    }

    /**
     * Sanitize with.
     *
     * @return Laravie\Codex\Contracts\Sanitizer
     */
    public function sanitizeWith(): SanitizerContract
    {
        return new Sanitizer();
    }
}
