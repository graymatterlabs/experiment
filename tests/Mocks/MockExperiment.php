<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests\Mocks;

use Closure;
use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;
use GrayMatterLabs\Experiment\Variant;

class MockExperiment implements Experiment
{
    protected string $name;

    protected array $variants;

    protected ?Closure $eligibility = null;

    protected bool $enabled = true;

    public function getIdentifier(): string|int
    {
        return 1;
    }

    public function getName(): string
    {
        if (isset($this->name)) {
            return $this->name;
        }

        $exploded = explode('\\', static::class);

        return end($exploded);
    }

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

    public function getVariantByName(string $name): ?VariantContract
    {
        foreach ($this->getVariants() as $instance) {
            if ($instance->equals($name)) {
                return $instance;
            }
        }

        return null;
    }

    public function getVariantByIdentifier(string|int $identifier): ?VariantContract
    {
        foreach ($this->getVariants() as $instance) {
            if ($instance->is($identifier)) {
                return $instance;
            }
        }

        return null;
    }

    public function apply(Sample $sample, VariantContract $variant): void
    {
        //
    }
}
