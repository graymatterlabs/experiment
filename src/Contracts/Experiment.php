<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Experiment
{
    /**
     * Get the unique identifier of the experiment.
     */
    public function getIdentifier(): string|int;

    /**
     * Get the name of the experiment.
     */
    public function getName(): string;

    /**
     * Whether the sample is eligible for the experiment.
     */
    public function isEligible(Sample $sample): bool;

    /**
     * Whether the experiment is enabled.
     */
    public function isEnabled(): bool;

    /**
     * Get the experiments' variants.
     */
    public function getVariants(): array;

    /**
     * Perform any actions after the sample has been allocated.
     */
    public function apply(Sample $sample, Variant $variant): void;
}
