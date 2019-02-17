<?php

namespace Laravie\Codex;

use Psr\Http\Message\StreamInterface;

class Payload
{
    /**
     * Payload content.
     *
     * @var mixed
     */
    protected $content = null;

    /**
     * Construct a new payload.
     *
     * @param  mixed  $content
     */
    public function __construct($content = null)
    {
        $this->content = $content;
    }

    /**
     * Construct a new payload using static.
     *
     * @param  mixed  $content
     *
     * @return static
     */
    public static function make($content = null)
    {
        if ($content instanceof self) {
            return $content;
        }

        return new static($content);
    }

    /**
     * Get payload content.
     *
     * @param  array  $headers
     *
     * @return mixed
     */
    public function get(array $headers = [])
    {
        if ($this->content instanceof StreamInterface) {
            return $this->content;
        }

        if (isset($headers['Content-Type']) && $headers['Content-Type'] == 'application/json') {
            return $this->toJson();
        } elseif (\is_array($this->content)) {
            return $this->toHttpQueries();
        }

        return $this->content;
    }

    /**
     * Convert the content to JSON.
     *
     * @param  int  $options
     *
     * @return string
     */
    public function toJson($options = 0): string
    {
        return \json_encode($this->content, $options);
    }

    /**
     * Convert the content to http queries.
     *
     * @param  string|null  $prefix
     * @param  string  $separator
     *
     * @return string
     */
    public function toHttpQueries(?string $prefix = null, string $separator = '&'): string
    {
        return \http_build_query($this->content, $prefix, $separator);
    }
}
