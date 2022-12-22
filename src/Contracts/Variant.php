<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

interface Variant
{
    /**
     * Get the unique identifier of the variant.
     */
    public function getIdentifier(): string|int;

    /**
     * Get the name of the variant.
     */
    public function getName(): string;

    /**
     * Get the variant's weight.
     */
    public function getWeight(): int;
}
