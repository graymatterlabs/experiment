<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;

final class Variant implements VariantContract
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
}
