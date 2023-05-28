<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;

final class Variant implements VariantContract
{
    public function __construct(private string|int $identifier, public string $name, private int $weight = 1)
    {
    }

    public function getIdentifier(): string|int
    {
        return $this->identifier;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function is(string|int|VariantContract $variant): bool
    {
        if ($variant instanceof VariantContract) {
            $variant = $variant->getIdentifier();
        }

        if (is_numeric($identifier = $this->getIdentifier())) {
            return is_numeric($variant) && (int) $identifier === (int) $variant;
        }

        return strcasecmp($identifier, (string) $variant) === 0;
    }
}
