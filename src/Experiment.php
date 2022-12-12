<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Experiment as ExperimentContract;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Variant;

abstract class Experiment implements ExperimentContract
{
    protected string|int $identifier;

    protected string $name;

    public function getIdentifier(): string|int {
        if (! empty($this->identifier)) {
            return $this->identifier;
        }

        return $this->getName();
    }

    public function getName(): string
    {
        if (! empty($this->name)) {
            return $this->name;
        }

        $exploded = explode('\\', static::class);

        return $exploded[array_key_last($exploded)];
    }

    public function allocate(Sample $sample): ?Variant
    {
        if (! $this->isEnabled() || empty($this->getVariants()) || ! $this->isEligible($sample)) {
            return null;
        }

        $variants = [];

        foreach ($this->getVariants() as $variant) {
            for ($i = 0; $i < $variant->getWeight(); $i++) {
                $variants[] = $variant;
            }
        }

        if (empty($variants)) {
            return null;
        }

        $variant = $variants[array_rand($variants)];

        $this->apply($sample, $variant);

        return $variant;
    }

    public function isEligible(Sample $sample): bool
    {
        return true;
    }

    public function isEnabled(): bool
    {
        return true;
    }

    abstract public function getVariants(): array;

    public function getVariantByName(string $name): ?Variant
    {
        foreach ($this->getVariants() as $instance) {
            if ($instance->equals($name)) {
                return $instance;
            }
        }

        return null;
    }

    public function getVariantByIdentifier(string|int $identifier): ?Variant
    {
        foreach ($this->getVariants() as $instance) {
            if ($instance->is($identifier)) {
                return $instance;
            }
        }

        return null;
    }

    public function apply(Sample $sample, Variant $variant): void
    {
    }
}
