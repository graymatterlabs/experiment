<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Strategy
{
    /**
     * Execute the strategy for experiment allocation.
     */
    public function execute(Experiment $experiment, Sample $sample): ?Variant;
}
