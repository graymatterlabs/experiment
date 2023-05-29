<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Support;

use GrayMatterLabs\Experiment\Contracts\Variant;

final class Variants
{
    private array $variants;

    public function __construct(Variant ...$variants)
    {
        $this->variants = $variants;
    }

    public function whereName(string $name): ?Variant
    {
        return $this->first(fn ($variant) => strcasecmp($variant->getName(), $name) === 0);
    }

    public function whereIdentifier(int|string $identifier): ?Variant
    {
        return $this->first(function ($variant) use ($identifier) {
            if (is_numeric($identifier)) {
                return (int) $identifier === (int) $variant->getIdentifier();
            }

            return strcasecmp($variant->getIdentifier(), $identifier) === 0;
        });
    }

    public function isEmpty(): bool
    {
        return empty($this->variants);
    }

    public function toArray(): array
    {
        return $this->variants;
    }

    public function first(callable $callback = null): ?Variant
    {
        return array_values(array_filter($this->variants, $callback))[0] ?? null;
    }
}
