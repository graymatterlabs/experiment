<?php

declare(strict_types=1);

namespace GrayMatterLabs\Experiment\Tests\Mocks;

use GrayMatterLabs\Experiment\Contracts\Sample;

class MockSample implements Sample
{
    public function getIdentifier(): string|int
    {
        return 1;
    }
}
