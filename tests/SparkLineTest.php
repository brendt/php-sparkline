<?php

declare(strict_types=1);

namespace Brendt\SparkLine\Tests;

use Brendt\SparkLine\SparkLine;
use Brendt\SparkLine\SparkLineInterval;
use DateTimeImmutable;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Spatie\Period\Period;

final class SparkLineTest extends TestCase
{
    private function days(): Collection
    {
        return collect([
            new SparkLineInterval(
                count: 1,
                dateTime: new DateTimeImmutable('2022-01-01')
            ),
            new SparkLineInterval(
                count: 2,
                dateTime: new DateTimeImmutable('2022-01-02')
            ),
        ]);
    }

    /** @test */
    public function test_create_sparkline(): void
    {
        $sparkLine = SparkLine::new($this->days())->make();

        $this->assertStringContainsString('<svg', $sparkLine);
    }

    /** @test */
    public function test_colors(): void
    {
        $sparkLine = SparkLine::new($this->days())
            ->withColors('red', 'green', 'blue')
            ->make();

        $this->assertStringContainsString('<stop offset="0%" stop-color="red"></stop>', $sparkLine);
        $this->assertStringContainsString('<stop offset="33%" stop-color="green"></stop>', $sparkLine);
        $this->assertStringContainsString('<stop offset="66%" stop-color="blue"></stop>', $sparkLine);
    }

    /** @test */
    public function test_stroke_width(): void
    {
        $sparkLine = SparkLine::new($this->days())
            ->withStrokeWidth(50)
            ->make();

        $this->assertStringContainsString('stroke-width="50"', $sparkLine);
    }

    /** @test */
    public function test_dimensions(): void
    {
        $sparkLine = SparkLine::new($this->days())
            ->withDimensions(500, 501)
            ->make();

        $this->assertStringContainsString('width="500"', $sparkLine);
        $this->assertStringContainsString('height="501"', $sparkLine);
    }

    /** @test */
    public function test_get_period(): void
    {
        $sparkLine = SparkLine::new($this->days());

        $this->assertTrue(
            Period::fromString('[2022-01-01, 2022-01-02]')
                ->equals($sparkLine->getPeriod()),
        );
    }

    /** @test */
    public function test_get_total(): void
    {
        $sparkLine = SparkLine::new($this->days());

        $this->assertEquals(3, $sparkLine->getTotal());
    }
}
