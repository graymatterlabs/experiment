<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Strategies;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Strategy;
use GrayMatterLabs\Experiment\Contracts\Variant;

class WeightedStrategy implements Strategy
{
    public function execute(Experiment $experiment, Sample $sample): ?Variant
    {
        $variants = [];

        foreach ($experiment->getVariants() as $variant) {
            for ($i = 0; $i < $variant->getWeight(); $i++) {
                $variants[] = $variant;
            }
        }

        if (empty($variants)) {
            return null;
        }

        return $variants[array_rand($variants)];
    }
}
