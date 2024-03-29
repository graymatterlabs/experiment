<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests;

use GrayMatterLabs\Experiment\Contracts\Factory;
use GrayMatterLabs\Experiment\Factories\ClassFactory;
use GrayMatterLabs\Experiment\Factories\MemoizedFactory;
use GrayMatterLabs\Experiment\Manager;
use GrayMatterLabs\Experiment\Persistence\SimpleCachePersistence;
use GrayMatterLabs\Experiment\Strategies\WeightedStrategy;
use GrayMatterLabs\Experiment\Tests\Mocks\MockExperiment;
use GrayMatterLabs\Experiment\Tests\Mocks\MockSample;
use GrayMatterLabs\SimpleCache\ArrayCache;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    public function test_it_gets_a_new_variant(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $selected = [];

        for ($i = 0; $i < 10; $i++) {
            $selected[$manager->allocate(MockExperiment::class, $sample)->getVariant()->getName()] = true;
            $manager->deallocate(MockExperiment::class, $sample);
        }

        $this->assertCount(2, $selected);
        $this->assertArrayHasKey('TEST', $selected);
        $this->assertArrayHasKey('CONTROL', $selected);
    }

    public function test_it_gets_the_variant_specified(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $manager->force(MockExperiment::class, $sample, 'TEST');
        $this->assertEquals('TEST', $manager->allocate(MockExperiment::class, $sample)->getVariant()->getName());
    }

    public function test_it_determines_whether_a_sample_is_allocated(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $this->assertFalse($manager->isAllocated(MockExperiment::class, $sample));
        $manager->force(MockExperiment::class, $sample, 'TEST');
        $this->assertTrue($manager->isAllocated(MockExperiment::class, $sample));
    }

    public function test_it_gets_the_previously_allocated_variant(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $variant = $manager->allocate(MockExperiment::class, $sample)->getVariant();
        $this->assertEquals($variant, $manager->allocate(MockExperiment::class, $sample)->getVariant());
    }

    public function test_it_requires_the_experiment_have_variants(): void
    {
        $manager = $this->getManager($factory = $this->factory());
        $sample = new MockSample();
        $factory->make(MockExperiment::class)->setVariants([]);
        $this->assertNull($manager->allocate(MockExperiment::class, $sample)->getVariant());
        $this->assertNull($manager->getVariant(MockExperiment::class, $sample));
    }

    public function test_it_requires_the_sample_be_eligible(): void
    {
        $manager = $this->getManager($factory = $this->factory());
        $factory->make(MockExperiment::class)->setEligibilityCallback(fn () => false);
        $variant = $manager->allocate(MockExperiment::class, new MockSample())->getVariant();
        $this->assertNull($variant);
    }

    public function test_it_requires_the_experiment_be_enabled(): void
    {
        $manager = $this->getManager($factory = $this->factory());
        $factory->make(MockExperiment::class)->setEnabled(false);
        $variant = $manager->allocate(MockExperiment::class, new MockSample())->getVariant();
        $this->assertNull($variant);
    }

    public function getManager(Factory $factory = null): Manager
    {
        return new Manager(
            new SimpleCachePersistence(new ArrayCache()),
            $factory ?? $this->factory(),
            new WeightedStrategy()
        );
    }

    public function factory(): Factory
    {
        return new MemoizedFactory(new ClassFactory());
    }
}
