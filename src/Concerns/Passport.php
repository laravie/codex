<?php

namespace Laravie\Codex\Concerns;

trait Passport
{
    /**
     * API Client ID.
     *
     * @var string|null
     */
    protected $clientId;

    /**
     * API Client Secret.
     *
     * @var string|null
     */
    protected $clientSecret;

    /**
     * API Access Token.
     *
     * @var string|null
     */
    protected $accessToken;

    /**
     * Get Client ID.
     *
     * @return string|null
     */
    final public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * Set Client ID.
     *
     * @param  string|null  $clientId
     *
     * @return $this
     */
    final public function setClientId(?string $clientId): self
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get Client Secret.
     *
     * @return string|null
     */
    final public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    /**
     * Set Client Secret.
     *
     * @param  string|null  $clientSecret
     *
     * @return $this
     */
    final public function setClientSecret(?string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get access token.
     *
     * @return string|null
     */
    final public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set access token.
     *
     * @param string|null $accessToken
     *
     * @return $this
     */
    final public function setAccessToken(?string $accessToken): self
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
