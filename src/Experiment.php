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

    protected bool $isEnabled = true;

    public function getIdentifier(): string|int
    {
        if (isset($this->identifier)) {
            return $this->identifier;
        }

        return $this->getName();
    }

    public function getName(): string
    {
        if (isset($this->name)) {
            return $this->name;
        }

        $exploded = explode('\\', static::class);

        return end($exploded);
    }

    abstract public function isEligible(Sample $sample): bool;

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    abstract public function getVariants(): array;

    public function apply(Sample $sample, Variant $variant): void
    {
        // TODO: Implement apply() method.
    }
}
