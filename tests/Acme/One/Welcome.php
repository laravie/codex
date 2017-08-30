<?php

namespace Laravie\Codex\TestCase\Acme\One;

use Laravie\Codex\Request;

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
}
