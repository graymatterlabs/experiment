<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Contracts;

use Stringable;

interface Variant extends Stringable
{
    /**
     * Get the name of the variant.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Get the variant's weight.
     *
     * @return int
     */
    public function getWeight(): int;

    /**
     * Determine whether the variant values are equal.
     *
     * @param string|\GrayMatterLabs\Experiment\Contracts\Variant $variant
     *
     * @return bool
     */
    public function equals(string|Variant $variant): bool;
}
