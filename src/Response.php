<?php

namespace Laravie\Codex;

use BadMethodCallException;
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
        return $this;
    }

    /**
     * Convert response body to array.
     *
     * @return array
     */
    public function toArray()
    {
        $body = $this->getContent();

        return $this->hasSanitizer()
                    ? $this->getSanitizer()->to($body)
                    : $body;
    }

    /**
     * Get body.
     *
     * @return int
     */
    public function getBody()
    {
        return $this->original->getBody();
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
    public function getStatusCode()
    {
        return $this->original->getStatusCode();
    }

    /**
     * Call method under \Psr\Http\Message\ResponseInterface.
     *
     * @param  string  $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (! method_exists($this->original, $method)) {
            throw new BadMethodCallException("Method [{$method}] doesn't exists.");
        }

        return call_user_func_array([$this->original, $method], $parameters);
    }
}
