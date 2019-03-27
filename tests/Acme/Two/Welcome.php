<?php

namespace Laravie\Codex\Tests\Acme\Two;

use Laravie\Codex\Request;
use Laravie\Codex\Endpoint;
use Laravie\Codex\Contracts\Endpoint as EndpointContract;

class Welcome extends Request
{
    /**
     * Version namespace.
     *
     * @var string
     */
    protected $version = 'v2';

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
     * Show welcome.
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    public function legacyShow()
    {
        return $this->proxyRequestViaVersion('v1', function () {
            return $this->show();
        });
    }

    /**
     * Get API Header.
     *
     * @return array
     */
    protected function getApiHeaders(): array
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
     * @return \Laravie\Codex\Contracts\Endpoint
     */
    protected function getApiEndpoint($path = []): EndpointContract
    {
        return parent::getApiEndpoint([$this->getVersion(), $path]);
    }
}
