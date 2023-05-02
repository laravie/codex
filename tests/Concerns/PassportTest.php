<?php

namespace Laravie\Codex\Tests\Concerns;

use Laravie\Codex\Concerns\Passport;
use PHPUnit\Framework\TestCase;

class PassportTest extends TestCase
{
    /** @test */
    public function it_can_uses_client_id()
    {
        $passport = new class()
        {
            use Passport;
        };

        $this->assertNull($passport->getClientId());

        $passport->setClientId('homestead');

        $this->assertSame('homestead', $passport->getClientId());
    }

    /** @test */
    public function it_can_uses_client_secret()
    {
        $passport = new class()
        {
            use Passport;
        };

        $this->assertNull($passport->getClientSecret());

        $passport->setClientSecret('secret');

        $this->assertSame('secret', $passport->getClientSecret());
    }

    /** @test */
    public function it_can_uses_access_token()
    {
        $passport = new class()
        {
            use Passport;
        };

        $this->assertNull($passport->getAccessToken());

        $passport->setAccessToken('AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');

        $this->assertSame('AckfSECXIvnK5r28GVIWUAxmbBSjTsmF', $passport->getAccessToken());
    }
}
