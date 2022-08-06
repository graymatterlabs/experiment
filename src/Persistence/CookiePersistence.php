<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Persistence;

use GrayMatterLabs\Experiment\Contracts\Experiment;
use GrayMatterLabs\Experiment\Contracts\Persistence;
use GrayMatterLabs\Experiment\Contracts\Sample;
use GrayMatterLabs\Experiment\Contracts\Variant;

/*
 * Cookie-based persistence implementation that works only for web based experiments.
 */
class CookiePersistence implements Persistence
{
    public function setVariantForSample(Experiment $experiment, Sample $sample, Variant $variant): bool
    {
        setcookie($this->getCookieName($experiment), $variant->getName(), 2147483647, '/');

        return true;
    }

    public function getVariantForSample(Experiment $experiment, Sample $sample): ?Variant
    {
        if (! $name = $_SESSION[$this->getCookieName($experiment)] ?? null) {
            return null;
        }

        return $experiment->getVariant($name);
    }

    public function removeSampleFromExperiment(Experiment $experiment, Sample $sample): bool
    {
        setcookie($this->getCookieName($experiment), null, time() - 1, '/');

        return true;
    }

    protected function getCookieName(Experiment $experiment): string
    {
        return 'allocation:'.$experiment->getName();
    }
}
