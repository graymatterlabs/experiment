<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Experiment
{
    /**
     * Get the unique identifier of the experiment.
     */
    public function getIdentifier(): int|string;

    /**
     * Get the name of the experiment.
     */
    public function getName(): string;

    /**
     * Allocate the sample to the experiment.
     */
    public function allocate(Sample $sample): ?Variant;

    /**
     * Whether the sample is eligible for the experiment.
     */
    public function isEligible(Sample $sample): bool;

    /**
     * Whether the experiment is enabled.
     */
    public function isEnabled(): bool;

    /**
     * Get the variants.
     *
     * @return Variant[]
     */
    public function getVariants(): array;

    /**
     * Get the variant by name, if it exists.
     */
    public function getVariantByName(string $name): ?Variant;

    /**
     * Get the variant by it's unique identifier, if it exists.
     */
    public function getVariantByIdentifier(string|int $identifier);

    /**
     * Perform any actions after the sample has been allocated.
     */
    public function apply(Sample $sample, Variant $variant): void;
}
