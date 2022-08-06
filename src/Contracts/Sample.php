<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Sample
{
    /**
     * Get unique identifier of the sample.
     *
     * @return string|int
     */
    public function getIdentifier(): string|int;
}
