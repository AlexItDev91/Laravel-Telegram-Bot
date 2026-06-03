<?php

namespace AlexItDev91\LaravelTelegramBot\Laravel\Concerns;

use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Illuminate\Contracts\Container\Container;

trait ResolvesTelegramCacheRepository
{
    abstract private function container(): Container;

    private function cacheRepository(mixed $store): ?CacheRepository
    {
        $container = $this->container();

        if (is_string($store) && $store !== '' && $container->bound('cache')) {
            $cache = $container->make('cache');

            if (! is_object($cache) || ! method_exists($cache, 'store')) {
                return null;
            }

            $repository = $cache->store($store);

            return $repository instanceof CacheRepository ? $repository : null;
        }

        if ($container->bound(CacheRepository::class)) {
            return $container->make(CacheRepository::class);
        }

        if (! $container->bound('cache')) {
            return null;
        }

        $cache = $container->make('cache');

        if ($cache instanceof CacheRepository) {
            return $cache;
        }

        if (is_object($cache) && method_exists($cache, 'store')) {
            $repository = $cache->store();

            return $repository instanceof CacheRepository ? $repository : null;
        }

        return null;
    }
}
