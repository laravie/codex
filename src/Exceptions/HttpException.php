<?php

namespace Laravie\Codex\Exceptions;

use Exception;
use RuntimeException;
use InvalidArgumentException;
use Laravie\Codex\Contracts\Response;
use Psr\Http\Message\ResponseInterface;
use Http\Client\Exception as HttpClientException;

class HttpException extends RuntimeException implements HttpClientException
{
    /**
     * Response headers.
     *
     * @var \Laravie\Codex\Contracts\Response
     */
    private $response;

    /**
     * Construct a new HTTP exception.
     *
     * @param \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response  $response
     * @param string  $message
     * @param \Exception|null  $previous
     * @param int  $code
     */
    public function __construct(
        $response,
        $message = null,
        Exception $previous = null,
        $code = 0
    ) {
        parent::__construct($message, $code, $previous);

        $this->setResponse($response);
    }

    /**
     * Get status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * Get response object.
     *
     * @return \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response object.
     *
     * @param  \Psr\Http\Message\ResponseInterface|\Laravie\Codex\Contracts\Response  $response
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function setResponse($response)
    {
        if (! ($response instanceof Response || $response instanceof ResponseInterface)) {
            throw new InvalidArgumentException('$response is not an acceptable response object!');
        }

        $this->response = $response;

        return $this;
    }
}
