<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 17. 5. 2024
 * Time: 20:02
 */

namespace Brendt\SparkLine;

final class Precision
{
    public function __construct(public readonly Period $period, public readonly int $precision = 1)
    {
    }

    public function getKey(\DateTimeInterface $dateTime): string
    {
        $seconds = $dateTime->getTimestamp();
        $divider = $this->precision * $this->period->value;

        return (string)(round($seconds / $divider) * $divider);
    }

    public function getRangeKey(int $step): string
    {
        $divider = $this->precision * $this->period->value;
        $seconds = ($divider) * $step;
        $date = new \DateTimeImmutable("-{$seconds} seconds");
        $seconds = $date->getTimestamp();

        return (string)(round($seconds / $divider) * $divider);
    }
}
