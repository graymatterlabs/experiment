<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Factory
{
    /**
     * Make an instance of the specified experiment.
     */
    public function make(string $experiment): Experiment;
}
