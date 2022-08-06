<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests\Mocks;

use DateInterval;
use DateTime;
use Psr\SimpleCache\CacheInterface;

class ArrayCache implements CacheInterface
{
    protected array $cache = [];

    public function get(string $key, mixed $default = null): mixed
    {
        if (! $this->has($key)) {
            return $default;
        }

        if ($this->isExpired($key)) {
            $this->delete($key);

            return $default;
        }

        return @unserialize($this->cache[$key]['value'], ['allowed_classes' => true]);
    }

    public function set(string $key, mixed $value, DateInterval|int|null $ttl = null): bool
    {
        if ($value === null) {
            return false;
        }

        $value = serialize($value);

        if ($ttl instanceof DateInterval) {
            $ttl = (new DateTime())->add($ttl)->getTimestamp();
        }

        $this->cache[$key] = [
            'ttl' => $ttl,
            'value' => $value,
        ];

        return true;
    }

    public function delete(string $key): bool
    {
        unset($this->cache[$key]);

        return true;
    }

    public function clear(): bool
    {
        $this->cache = [];

        return true;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        foreach ($keys as $key) {
            yield $this->get($key, $default);
        }
    }

    public function setMultiple(iterable $values, DateInterval|int|null $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            $this->set($key, $value, $ttl);
        }

        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->cache);
    }

    protected function isExpired(string $key): bool
    {
        $ttl = $this->cache[$key]['ttl'] ?? null;

        return $ttl && $ttl < time();
    }
}
