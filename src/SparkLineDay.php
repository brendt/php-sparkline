<?php

declare(strict_types=1);

namespace Brendt\SparkLine;

use DateTimeImmutable;

final class SparkLineDay
{
    public function __construct(
        public readonly int $count,
        public readonly DateTimeImmutable $day,
    ) {
    }

    public function rebase(int $base, int $max): self
    {
        return new self(
            count: (int) floor($this->count * ($base / $max)),
            day: $this->day,
        );
    }
}
