<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Factory;
use GrayMatterLabs\Experiment\Contracts\Persistence;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Strategy;
use GrayMatterLabs\Experiment\Contracts\Variant;
use InvalidArgumentException;

class Manager
{
    public function __construct(protected Persistence $persistence, protected Factory $factory, protected Strategy $strategy)
    {
    }

    public function allocate(string $experiment, Sample $sample): ?Variant
    {
        $instance = $this->getExperimentInstance($experiment);

        if ($variant = $this->persistence->getVariantForSample($instance, $sample)) {
            return $variant;
        }

        if (! $instance->isEnabled() || empty($instance->getVariants()) || ! $instance->isEligible($sample)) {
            return null;
        }

        if (! $variant = $this->strategy->execute($instance, $sample)) {
            return null;
        }

        $instance->apply($sample, $variant);

        $this->persistence->setVariantForSample($instance, $sample, $variant);

        return $variant;
    }

    public function getVariant(string $experiment, Sample $sample): ?Variant
    {
        return $this->persistence->getVariantForSample($this->getExperimentInstance($experiment), $sample);
    }

    public function isAllocated(string $experiment, Sample $sample): bool
    {
        return $this->persistence->getVariantForSample($this->getExperimentInstance($experiment), $sample) !== null;
    }

    public function force(string $experiment, Sample $sample, string $variant): void
    {
        $instance = $this->getExperimentInstance($experiment);

        if (! $variantInstance = $instance->getVariantByName($variant)) {
            throw new InvalidArgumentException(sprintf('Unable to force invalid variant [%s].', $variant));
        }

        if ($this->persistence->getVariantForSample($instance, $sample) !== null) {
            $this->persistence->removeSampleFromExperiment($instance, $sample);
        }

        $this->persistence->setVariantForSample($instance, $sample, $variantInstance);
    }

    public function deallocate(string $experiment, Sample $sample): void
    {
        $this->persistence->removeSampleFromExperiment($this->getExperimentInstance($experiment), $sample);
    }

    public function getExperimentInstance(string $experiment): Experiment
    {
        return $this->factory->make($experiment);
    }
}
