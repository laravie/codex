<?php

namespace Laravie\Codex\TestCase\Acme\Two;

use Laravie\Codex\Request;
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
     * Get API Header.
     *
     * @return array
     */
    protected function getApiHeaders()
    {
        return [
            'Authorization' => 'Bearer '.$this->client->getApiKey(),
        ];
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
        return parent::getApiEndpoint([$this->getVersion(), $path]);
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
