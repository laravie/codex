<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Sanitizer;

trait WithSanitizer
{
    /**
     * The Sanitizer.
     *
     * @var \Laravie\Codex\Sanitizer
     */
    protected $sanitizer;

    /**
     * Check if sanitizaer exists.
     *
     * @return bool
     */
    public function hasSanitizer()
    {
        return $this->sanitizer instanceof Sanitizer;
    }

    /**
     * Set sanitizer.
     *
     * @param  \Laravie\Codex\Sanitizer|null  $sanitizer
     *
     * @return $this
     */
    public function setSanitizer(Sanitizer $sanitizer = null)
    {
        $this->sanitizer = $sanitizer;

        return $this;
    }

    /**
     * Get sanitizer.
     *
     * @return \Laravie\Codex\Sanitizer|null
     */
    public function getSanitizer()
    {
        return $this->sanitizer;
    }
}
