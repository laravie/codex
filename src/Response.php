<?php

namespace Laravie\Codex;

use Laravie\Codex\Common\Response as CommonResponse;

class Response extends CommonResponse
{
    use Support\WithSanitizer;

    /**
     * Convert response body to array.
     *
     * @return array
     */
    public function toArray(): array
    {
        $content = parent::toArray();

        return ! empty($content) ? $this->sanitizeTo($content) : $content;
    }
}
