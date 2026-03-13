<?php

declare(strict_types=1);

namespace Zaimea\SDK\Groups\Support;

use RuntimeException;

final class ProductionSecurityChecks
{
    public static function assertForEnvironment(string $environment): void
    {
        if ($environment !== 'production') {
            return;
        }

        if ((bool) config('app.debug', false)) {
            throw new RuntimeException('In production, APP_DEBUG must be false.');
        }

        if (! (bool) config('groups.security.force_https', false)) {
            throw new RuntimeException('In production, SECURITY_FORCE_HTTPS must be enabled.');
        }

        $appUrl = mb_strtolower((string) config('app.url', ''));
        if (! str_starts_with($appUrl, 'https://')) {
            throw new RuntimeException('In production, APP_URL must use https://.');
        }
    }
}