<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Persistence
{
    /**
     * Set the variant for the sample.
     */
    public function setVariantForSample(Experiment $experiment, Sample $sample, Variant $variant): bool;

    /**
     * Get the variant for the sample if one exists.
     */
    public function getVariantForSample(Experiment $experiment, Sample $sample): ?Variant;

    /**
     * Remove the sample from the experiment.
     */
    public function removeSampleFromExperiment(Experiment $experiment, Sample $sample): bool;
}
