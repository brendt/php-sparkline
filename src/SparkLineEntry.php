<?php

declare(strict_types=1);

namespace Brendt\SparkLine;

final class SparkLineEntry
{
    public function __construct(
        public readonly int $count,
    ) {
    }

    public function rebase(int $base, int $max): self
    {
        return new self(
            count: (int) floor($this->count * ($base / $max)),
        );
    }
}
