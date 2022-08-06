<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;

class Variant implements VariantContract
{
    public function __construct(protected string $name, protected int $weight = 1)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function equals(string|VariantContract $variant): bool
    {
        if ($variant instanceof VariantContract) {
            $variant = $variant->getName();
        }

        return strcasecmp($this->getName(), $variant) === 0;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
