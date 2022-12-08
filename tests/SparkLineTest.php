<?php

declare(strict_types=1);

namespace Brendt\SparkLine\Tests;

use Brendt\SparkLine\SparkLine;
use Brendt\SparkLine\SparkLineDay;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

final class SparkLineTest extends TestCase
{
    private function days(): Collection
    {
        return collect([
            new SparkLineDay(
                count: 1,
                day: new DateTimeImmutable('-2 days')
            ),
            new SparkLineDay(
                count: 2,
                day: new DateTimeImmutable('-1 day')
            ),
        ]);
    }

    /** @test */
    public function it_creates_a_sparkline(): void
    {
        $sparkLine = SparkLine::new($this->days())->make();

        $this->assertStringContainsString('<svg', $sparkLine);
    }
}
