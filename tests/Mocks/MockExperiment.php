<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests\Mocks;

use Closure;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Experiment;
use GrayMatterLabs\Experiment\Variant;

class MockExperiment extends Experiment
{
    protected array $variants = [];

    protected ?Closure $eligibility = null;

    protected bool $enabled = true;

    public function getVariants(): array
    {
        return $this->variants ?: [
            new Variant('CONTROL'),
            new Variant('TEST'),
        ];
    }

    public function setVariants(array $variants): static
    {
        $this->variants = $variants;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function isEligible(Sample $sample): bool
    {
        $callback = $this->eligibility;

        return $callback ? $callback($sample) : true;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEligibilityCallback(Closure $eligibility): static
    {
        $this->eligibility = $eligibility;

        return $this;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }
}
