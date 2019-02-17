<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Response as ResponseContract;

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
    protected function proxyRequestViaVersion(string $swapVersion, callable $callback): ResponseContract
    {
        $version = $this->version;

        try {
            $this->version = $swapVersion;

            return \call_user_func($callback);
        } finally {
            $this->version = $version;
        }
    }
}
