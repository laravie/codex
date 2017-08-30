<?php

namespace Laravie\Codex\Contracts;

interface Sanitizable
{
    /**
     * Resolve the sanitizer class.
     *
     * @return \Laravie\Codex\Sanitizer|null
     */
    protected function sanitizeWith();
}
