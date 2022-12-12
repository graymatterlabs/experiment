<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests;

use GrayMatterLabs\Experiment\Factories\MemoizedFactory;
use GrayMatterLabs\Experiment\Factories\ClassFactory;
use GrayMatterLabs\Experiment\Manager;
use GrayMatterLabs\Experiment\Persistence\SimpleCachePersistence;
use GrayMatterLabs\Experiment\Tests\Mocks\MockExperiment;
use GrayMatterLabs\Experiment\Tests\Mocks\MockSample;
use GrayMatterLabs\SimpleCache\ArrayCache;
use PHPUnit\Framework\TestCase;

class ManagerTest extends TestCase
{
    public function test_it_gets_the_same_variant_multiple_times(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $variant = $manager->allocate(MockExperiment::class, $sample);
        $this->assertEquals($variant, $manager->allocate(MockExperiment::class, $sample));
    }

    public function test_it_gets_a_new_variant(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $selected = [];

        for ($i = 0; $i < 10; $i++) {
            $selected[$manager->allocate(MockExperiment::class, $sample)->getName()] = true;
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
        $this->assertTrue($manager->allocate(MockExperiment::class, $sample)->equals('TEST'));
    }

    public function test_it_determines_whether_a_sample_is_allocated(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $this->assertFalse($manager->isAllocated(MockExperiment::class, $sample));
        $manager->force(MockExperiment::class, $sample, 'TEST');
        $this->assertTrue($manager->isAllocated(MockExperiment::class, $sample));
    }

    public function test_it_gets_the_allocated_variant(): void
    {
        $manager = $this->getManager();
        $sample = new MockSample();
        $this->assertNull($manager->getVariant(MockExperiment::class, $sample));
        $manager->force(MockExperiment::class, $sample, 'TEST');
        $this->assertNotNull($manager->getVariant(MockExperiment::class, $sample));
    }

    public function getManager(): Manager
    {
        return new Manager(
            new SimpleCachePersistence(new ArrayCache()),
            new MemoizedFactory(new ClassFactory())
        );
    }
}
