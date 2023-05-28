<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Factories;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Factory;
use InvalidArgumentException;

final class ClassFactory implements Factory
{
    public function make(string $experiment): Experiment
    {
        if (! class_exists($experiment) || ! is_subclass_of($experiment, Experiment::class)) {
            throw new InvalidArgumentException(sprintf('Unable to make invalid experiment [%s].', $experiment));
        }

        return new $experiment();
    }
}
