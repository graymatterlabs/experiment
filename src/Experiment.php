<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Experiment as ExperimentContract;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Variant;

abstract class Experiment implements ExperimentContract
{
    protected string $name;

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

        return $variants[array_rand($variants)];
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

    public function getVariant(string $variant): ?Variant
    {
        foreach ($this->getVariants() as $instance) {
            if ($instance->equals($variant)) {
                return $instance;
            }
        }

        return null;
    }
}
