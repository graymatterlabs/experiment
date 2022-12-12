<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Sample
{
    /**
     * Get the unique identifier of the sample.
     */
    public function getIdentifier(): string|int;
}
