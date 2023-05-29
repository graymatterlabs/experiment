<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Factories;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Factory;

final class MemoizedFactory implements Factory
{
    /**
     * @var Experiment[]
     */
    private array $cache = [];

    public function __construct(private Factory $factory)
    {
    }

    public function make(string $experiment): Experiment
    {
        if (! array_key_exists($experiment, $this->cache)) {
            $this->cache[$experiment] = $this->factory->make($experiment);
        }

        return $this->cache[$experiment];
    }
}
