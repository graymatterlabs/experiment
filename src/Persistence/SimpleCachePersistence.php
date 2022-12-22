<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Persistence;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Persistence;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Variant;
use GrayMatterLabs\Experiment\Support\Variants;
use Psr\SimpleCache\CacheInterface;

class SimpleCachePersistence implements Persistence
{
    public function __construct(protected CacheInterface $cache)
    {
    }

    public function setVariantForSample(Experiment $experiment, Sample $sample, Variant $variant): bool
    {
        return $this->cache->set($this->getAllocationCacheKey($experiment, $sample), $variant->getName());
    }

    public function getVariantForSample(Experiment $experiment, Sample $sample): ?Variant
    {
        if (! $name = $this->cache->get($this->getAllocationCacheKey($experiment, $sample))) {
            return null;
        }

        return (new Variants(...$experiment->getVariants()))->whereName($name);
    }

    public function removeSampleFromExperiment(Experiment $experiment, Sample $sample): bool
    {
        return $this->cache->delete($this->getAllocationCacheKey($experiment, $sample));
    }

    protected function getAllocationCacheKey(Experiment $experiment, Sample $sample): string
    {
        return sprintf('allocation:%s:%s', $experiment->getName(), $sample->getIdentifier());
    }
}
