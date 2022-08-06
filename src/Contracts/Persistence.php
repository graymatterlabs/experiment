<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Persistence
{
    /**
     * Set the variant for the sample.
     *
     * @param \GrayMatterLabs\Experiment\Contracts\Experiment $experiment
     * @param \GrayMatterLabs\Experiment\Contracts\Sample $sample
     * @param \GrayMatterLabs\Experiment\Contracts\Variant $variant
     *
     * @return bool
     */
    public function setVariantForSample(Experiment $experiment, Sample $sample, Variant $variant): bool;

    /**
     * Get the variant for the sample if one exists.
     *
     * @param \GrayMatterLabs\Experiment\Contracts\Experiment $experiment
     * @param \GrayMatterLabs\Experiment\Contracts\Sample $sample
     *
     * @return \GrayMatterLabs\Experiment\Contracts\Variant|null
     */
    public function getVariantForSample(Experiment $experiment, Sample $sample): ?Variant;

    /**
     * Remove the sample from the experiment.
     *
     * @param \GrayMatterLabs\Experiment\Contracts\Experiment $experiment
     * @param \GrayMatterLabs\Experiment\Contracts\Sample $sample
     *
     * @return bool
     */
    public function removeSampleFromExperiment(Experiment $experiment, Sample $sample): bool;
}
