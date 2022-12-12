<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;

class Variant implements VariantContract
{
    public function __construct(protected string|int $identifier, public string $name, protected int $weight = 1)
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

    public function equals(string $name): bool
    {
        return strcasecmp($this->getName(), $name) === 0;
    }
}
