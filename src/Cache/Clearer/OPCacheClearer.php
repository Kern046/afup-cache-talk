<?php

declare(strict_types=1);

namespace App\Cache\Clearer;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\CacheClearer\CacheClearerInterface;

final readonly class OPCacheClearer implements CacheClearerInterface
{
    public function __construct(
        private LoggerInterface $logger,
        #[Autowire('%kernel.project_dir%')]
        private string $projectDir,
    ) {
    }

    public function clear(string $cacheDir): void
    {
        if (!extension_loaded('Zend OPcache')) {
            return;
        }
        $wasReset = opcache_reset();

        $envFile = $this->projectDir . '/.env.local.php';

        $isEnvFileInCache = is_file($envFile) && opcache_is_script_cached($envFile);

        opcache_invalidate($envFile, true);

        $this->logger->debug('OPCache reset status : {wasReset}; Env file in cache : {isEnvFileInCache}', [
            'wasReset' => intval($wasReset),
            'isEnvFileInCache' => intval($isEnvFileInCache),
        ]);
    }
}