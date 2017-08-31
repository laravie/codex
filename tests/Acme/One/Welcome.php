<?php

namespace Laravie\Codex\TestCase\Acme\One;

use Laravie\Codex\Request;
use Laravie\Codex\Endpoint;
use Laravie\Codex\Sanitizer;

class Welcome extends Request
{
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
     *
     * @return \Laravie\Codex\Endpoint
     */
    protected function getApiEndpoint($path = [])
    {
        if (is_array($path)) {
            array_unshift($path, $this->getVersion());
        } else {
            $path = [$this->getVersion(), $path];
        }

        return parent::getApiEndpoint($path);
    }

    /**
     * Sanitize with.
     *
     * @return Laravie\Codex\Contracts\Sanitizer
     */
    public function sanitizeWith()
    {
        return new Sanitizer();
    }
}
