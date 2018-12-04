<?php

namespace Laravie\Codex;

use BadMethodCallException;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;

class Response implements Contracts\Response
{
    use Support\WithSanitizer;

    /**
     * The original response.
     *
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $original;

    /**
     * Construct a new response.
     *
     * @param \Psr\Http\Message\ResponseInterface  $original
     */
    public function __construct(ResponseInterface $original)
    {
        $this->original = $original;
    }

    /**
     * Validate the response object.
     *
     * @return $this
     */
    public function validate()
    {
        $this->abortIfRequestUnauthorized();

        return $this;
    }

    /**
     * Validate response with custom callable.
     *
     * @param  callable  $callback
     *
     * @return $this
     */
    final public function validateWith(callable $callback): self
    {
        call_user_func($callback, $this->getStatusCode(), $this);

        return $this;
    }

    /**
     * Convert response body to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $content = $this->getContent();

        return is_array($content) ? $this->sanitizeTo($content) : [];
    }

    /**
     * Get body.
     *
     * @return string
     */
    public function getBody()
    {
        $content = $this->original->getBody();

        return $content instanceof StreamInterface
                    ? (string) $content
                    : $content;
    }

    /**
     * Get content from body, by default we assume it returning JSON.
     *
     * @return mixed
     */
    public function getContent()
    {
        return json_decode($this->getBody(), true);
    }

    /**
     * Get status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->original->getStatusCode();
    }

    /**
     * Check if response is unauthorized.
     *
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return in_array($this->getStatusCode(), [200, 201, 202, 204, 205]);
    }

    /**
     * Check if response is missing.
     *
     * @return bool
     */
    public function isNotFound(): bool
    {
        return in_array($this->getStatusCode(), [404]);
    }

    /**
     * Check if response is unauthorized.
     *
     * @return bool
     */
    public function isUnauthorized(): bool
    {
        return in_array($this->getStatusCode(), [401, 403]);
    }

    /**
     * Validate for unauthorized request.
     *
     * @throws \Laravie\Codex\Exceptions\UnauthorizedException
     *
     * @return void
     */
    public function abortIfRequestUnauthorized(): void
    {
        if ($this->isUnauthorized()) {
            throw new Exceptions\UnauthorizedException($this);
        }
    }

    /**
     * Validate for unauthorized request.
     *
     * @param  string|null  $message
     *
     * @throws \Laravie\Codex\Exceptions\HttpException
     *
     * @return void
     */
    public function abortIfRequestHasFailed(?string $message = null): void
    {
        $statusCode = $this->getStatusCode();

        if ($statusCode >= 400 && $statusCode < 600) {
            throw new Exceptions\HttpException($this, $message);
        }
    }

    /**
     * Abort if request data is not found.
     *
     * @param  string|null $message
     *
     * @return void
     */
    public function abortIfRequestNotFound(?string $message = null): void
    {
        if ($this->isNotFound()) {
            throw new Exceptions\NotFoundException($this, $message);
        }
    }

    /**
     * Call method under \Psr\Http\Message\ResponseInterface.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (! method_exists($this->original, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        return $this->original->{$method}(...$parameters);
    }
}
