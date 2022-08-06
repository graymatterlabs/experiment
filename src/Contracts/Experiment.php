<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Experiment
{
    /**
     * Get the name of the experiment.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Allocate the sample to the experiment.
     *
     * @param \GrayMatterLabs\Experiment\Contracts\Sample $sample
     *
     * @return \GrayMatterLabs\Experiment\Contracts\Variant|null
     */
    public function allocate(Sample $sample): ?Variant;

    /**
     * Whether the sample is eligible for the experiment.
     *
     * @param \GrayMatterLabs\Experiment\Contracts\Sample $sample
     *
     * @return bool
     */
    public function isEligible(Sample $sample): bool;

    /**
     * Whether the experiment is enabled.
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Get the variants keyed by name.
     *
     * @return array<string, Variant>
     */
    public function getVariants(): array;

    /**
     * Get the variant by name, if it exists.
     *
     * @param string $variant
     *
     * @return \GrayMatterLabs\Experiment\Contracts\Variant|null
     */
    public function getVariant(string $variant): ?Variant;
}
