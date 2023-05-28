<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;

final class Allocation
{
    public function __construct(
        private Sample $sample,
        private Experiment $experiment,
        private ?VariantContract $variant = null,
        private bool $wasRecentlyAllocated = false
    ) {
    }

    public function getSample(): Sample
    {
        return $this->sample;
    }

    public function getExperiment(): Experiment
    {
        return $this->experiment;
    }

    public function getVariant(): ?VariantContract
    {
        return $this->variant;
    }

    public function wasRecentlyAllocated(): bool
    {
        return $this->wasRecentlyAllocated;
    }

    public function isAllocated(): bool
    {
        return $this->variant !== null;
    }

    public function isVariant(string|int|VariantContract $variant): bool
    {
        return (bool) $this->variant?->is($variant);
    }
}
