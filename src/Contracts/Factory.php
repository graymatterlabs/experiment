<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Factory
{
    /**
     * Make an instance of the specified experiment.
     *
     * @param string $experiment
     *
     * @return \GrayMatterLabs\Experiment\Contracts\Experiment
     */
    public function make(string $experiment): Experiment;
}
