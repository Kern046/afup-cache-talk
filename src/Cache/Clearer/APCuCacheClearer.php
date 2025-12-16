<?php

declare(strict_types=1);

namespace App\Cache\Clearer;

use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

final class APCuCacheClearer implements CacheClearerInterface
{
    public function clear(string $cacheDir): void
    {
        if (!function_exists('apcu_clear_cache')) {
            return;
        }

        apcu_clear_cache();
    }
}