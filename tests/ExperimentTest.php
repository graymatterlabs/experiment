<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests;

use GrayMatterLabs\Experiment\Contracts\Factory;
use GrayMatterLabs\Experiment\Contracts\Variant as VariantContract;
use GrayMatterLabs\Experiment\Factories\MemoizedFactory;
use GrayMatterLabs\Experiment\Factories\ClassFactory;
use GrayMatterLabs\Experiment\Tests\Mocks\MockExperiment;
use GrayMatterLabs\Experiment\Tests\Mocks\MockSample;
use GrayMatterLabs\Experiment\Variant;
use PHPUnit\Framework\TestCase;

class ExperimentTest extends TestCase
{
    public function test_it_has_a_name(): void
    {
        $experiment = $this->factory()->make(MockExperiment::class);

        $this->assertEquals('MockExperiment', $experiment->getName());

        $experiment->setName('Unique Name');
        $this->assertEquals('Unique Name', $experiment->getName());
    }

    public function test_it_has_variants(): void
    {
        $variants = $this->factory()->make(MockExperiment::class)->getVariants();

        $this->assertCount(2, $variants);

        foreach ($variants as $variant) {
            $this->assertInstanceOf(VariantContract::class, $variant);
            $this->assertArrayHasKey($variant->getName(), array_flip(['CONTROL', 'TEST']));
        }
    }

    public function test_it_allocates_to_a_variant(): void
    {
        $variant = $this->factory()->make(MockExperiment::class)->allocate(new MockSample());
        $this->assertInstanceOf(VariantContract::class, $variant);
        $this->assertArrayHasKey($variant->getName(), array_flip(['CONTROL', 'TEST']));
    }

    public function test_it_favors_the_variant_with_highest_weight(): void
    {
        $experiment = $this->factory()->make(MockExperiment::class)->setVariants([
            new Variant(1, 'CONTROL', 10),
            new Variant(2, 'TEST', 0),
        ]);

        $this->assertTrue($experiment->allocate(new MockSample())->equals('CONTROL'));
    }

    public function test_it_requires_the_sample_be_eligible(): void
    {
        $experiment = $this->factory()->make(MockExperiment::class);
        $experiment->setEligibilityCallback(fn () => false);
        $variant = $experiment->allocate(new MockSample());
        $this->assertNull($variant);
    }

    public function test_it_requires_the_experiment_be_enabled(): void
    {
        $experiment = $this->factory()->make(MockExperiment::class);
        $experiment->setEnabled(false);
        $variant = $experiment->allocate(new MockSample());
        $this->assertNull($variant);
    }

    public function test_it_gets_a_variant_by_name(): void
    {
        $experiment = $this->factory()->make(MockExperiment::class);
        $this->assertInstanceOf(VariantContract::class, $experiment->getVariantByName('CONTROL'));
    }

    public function test_it_gets_a_variant_by_identifier(): void
    {
        $experiment = $this->factory()->make(MockExperiment::class);
        $this->assertInstanceOf(VariantContract::class, $experiment->getVariantByIdentifier(1));
    }

    public function factory(): Factory
    {
        return new MemoizedFactory(new ClassFactory());
    }
}
