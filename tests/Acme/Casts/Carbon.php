<?php

namespace Laravie\Codex\TestCase\Acme\Casts;

use Laravie\Codex\Cast;

class Carbon extends Cast
{
    /**
     * Is value a valid object.
     *
     * @param  mixed  $value
     *
     * @return bool
     */
    protected function isValid($value)
    {
        return $value instanceof \DateTimeInterface;
    }

    /**
     * Cast value from object.
     *
     * @param  object  $value
     *
     * @return mixed
     */
    protected function fromCast($value)
    {
        return $value->format('Y-m-d');
    }

    /**
     * Cast value to object.
     *
     * @param  object  $value
     *
     * @return mixed
     */
    protected function toCast($value)
    {
        return new \DateTime($value);
    }
}
