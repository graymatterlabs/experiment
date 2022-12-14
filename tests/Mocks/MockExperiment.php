<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests\Mocks;

use Closure;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Experiment;
use GrayMatterLabs\Experiment\Variant;

class MockExperiment extends Experiment
{
    protected string|int $identifier = 1;

    protected array $variants;

    protected ?Closure $eligibility = null;

    public function getVariants(): array
    {
        return $this->variants ?? [
            new Variant(1, 'CONTROL'),
            new Variant(2, 'TEST'),
        ];
    }

    public function setVariants(array $variants): static
    {
        $this->variants = $variants;

        return $this;
    }

    public function isEligible(Sample $sample): bool
    {
        $callback = $this->eligibility;

        return $callback ? $callback($sample) : true;
    }

    public function setEligibilityCallback(Closure $eligibility): static
    {
        $this->eligibility = $eligibility;

        return $this;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->isEnabled = $enabled;

        return $this;
    }
}
