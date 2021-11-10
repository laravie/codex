<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Response;

trait Versioning
{
    /**
     * Get API version.
     *
     * @return string
     */
    final public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * Proxy route to response via other version.
     *
     * @param  string   $swapVersion
     * @param  callable $callback
     *
     * @return \Laravie\Codex\Contracts\Response
     */
    protected function proxyRequestViaVersion(string $swapVersion, callable $callback): Response
    {
        $version = $this->version;

        try {
            $this->version = $swapVersion;

            /** @var \Laravie\Codex\Contracts\Response $response */
            $response = \call_user_func($callback);
        } finally {
            $this->version = $version;
        }

        return $response;
    }
}
