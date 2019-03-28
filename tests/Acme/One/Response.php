<?php

namespace Laravie\Codex\Tests\Acme\One;

use Laravie\Codex\Contracts\Filterable;
use Laravie\Codex\Filter\WithSanitizer;

class Response extends \Laravie\Codex\Response implements Filterable
{
    use WithSanitizer;
}
