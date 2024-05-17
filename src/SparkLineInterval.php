<?php

declare(strict_types=1);

namespace Brendt\SparkLine;

use DateTimeInterface;

final class SparkLineInterval
{
    public function __construct(
        public readonly int               $count,
        public readonly DateTimeInterface $dateTime,
    ) {
    }

    public function rebase(int $base, int $max): self
    {
        return new self(
            count: (int) floor($this->count * ($base / $max)),
            dateTime: $this->dateTime,
        );
    }
}
