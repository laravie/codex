<?php

namespace Laravie\Codex\Exceptions;

use Exception;
use RuntimeException;
use Http\Client\Exception;

class HttpException extends RuntimeException implements Exception
{
    /**
     * Response headers.
     *
     * @var \Laravie\Codex\Response
     */
    private $response;

    /**
     * Construct a new HTTP exception.
     *
     * @param int  $statusCode
     * @param string  $message
     * @param \Exception|null  $previous
     * @param \Laravie\Codex\Response  $response
     * @param int  $code
     */
    public function __construct(
        Response $response,
        $message = null,
        Exception $previous = null,
        $code = 0
    ) {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
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
     * @return \Laravie\Codex\Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set response object.
     *
     * @param  \Laravie\Codex\Response $response
     *
     * @return $this
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;

        return $this;
    }
}
