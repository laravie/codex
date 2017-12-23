<?php

namespace Laravie\Codex\Support;

use Laravie\Codex\Contracts\Sanitizer;

trait WithSanitizer
{
    /**
     * The Sanitizer.
     *
     * @var \Laravie\Codex\Contracts\Sanitizer
     */
    protected $sanitizer;

    /**
     * Check if sanitizaer exists.
     *
     * @return bool
     */
    public function hasSanitizer(): bool
    {
        return $this->sanitizer instanceof Sanitizer;
    }

    /**
     * Set sanitizer.
     *
     * @param  \Laravie\Codex\Contracts\Sanitizer|null  $sanitizer
     *
     * @return $this
     */
    public function setSanitizer(?Sanitizer $sanitizer = null): self
    {
        $this->sanitizer = $sanitizer;

        return $this;
    }

    /**
     * Get sanitizer.
     *
     * @return \Laravie\Codex\Contracts\Sanitizer|null
     */
    public function getSanitizer(): ?Sanitizer
    {
        return $this->sanitizer;
    }

    /**
     * Sanitize "from" for content.
     *
     * @param  array|mixed  $content
     *
     * @return array|object
     */
    public function sanitizeFrom($content)
    {
        return ($this->hasSanitizer() && is_array($content))
                    ? $this->sanitizer->from($content)
                    : $content;
    }

    /**
     * Sanitize "to" for content.
     *
     * @param  array|mixed  $content
     *
     * @return array
     */
    public function sanitizeTo($content): array
    {
        return ($this->hasSanitizer() && is_array($content))
                    ? $this->sanitizer->to($content)
                    : $content;
    }
}
