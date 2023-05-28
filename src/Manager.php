<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Factory;
use GrayMatterLabs\Experiment\Contracts\Persistence;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Strategy;
use GrayMatterLabs\Experiment\Contracts\Variant;
use GrayMatterLabs\Experiment\Support\Variants;
use InvalidArgumentException;

final class Manager
{
    public function __construct(private Persistence $persistence, private Factory $factory, private Strategy $strategy)
    {
    }

    public function allocate(string $experiment, Sample $sample): Allocation
    {
        $instance = $this->getExperimentInstance($experiment);

        if ($variant = $this->persistence->getVariantForSample($instance, $sample)) {
            return new Allocation($sample, $instance, $variant);
        }

        if (! $instance->isEnabled() || empty($instance->getVariants()) || ! $instance->isEligible($sample)) {
            return new Allocation($sample, $instance);
        }

        if (! $variant = $this->strategy->execute($instance, $sample)) {
            return new Allocation($sample, $instance);
        }

        $instance->apply($sample, $variant);

        $this->persistence->setVariantForSample($instance, $sample, $variant);

        return new Allocation($sample, $instance, $variant, true);
    }

    public function getVariant(string $experiment, Sample $sample): ?Variant
    {
        return $this->persistence->getVariantForSample($this->getExperimentInstance($experiment), $sample);
    }

    public function isAllocated(string $experiment, Sample $sample): bool
    {
        return $this->persistence->getVariantForSample($this->getExperimentInstance($experiment), $sample) !== null;
    }

    public function force(string $experiment, Sample $sample, string $variantName): void
    {
        $instance = $this->getExperimentInstance($experiment);

        if (! $variantInstance = (new Variants(...$instance->getVariants()))->whereName($variantName)) {
            throw new InvalidArgumentException(sprintf('Unable to force invalid variant [%s].', $variantName));
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
