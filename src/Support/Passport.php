<?php

namespace Laravie\Codex\Support;

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
    public function getClientId(): ?string
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
    public function setClientId(?string $clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * Get Client Secret.
     *
     * @return string|null
     */
    public function getClientSecret(): ?string
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
    public function setClientSecret(?string $clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * Get access token.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
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
    public function setAccessToken(?string $accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }
}
