<?php

declare(strict_types=1);

namespace Brendt\SparkLine\Tests;

use Brendt\SparkLine\SparkLine;
use Brendt\SparkLine\SparkLineEntry;
use PHPUnit\Framework\TestCase;

final class SparkLineTest extends TestCase
{
    private function entries(): array
    {
        return [
            new SparkLineEntry(
                count: 1,
            ),
            new SparkLineEntry(
                count: 2,
            ),
        ];
    }

    /** @test */
    public function test_create_sparkline(): void
    {
        $sparkLine = (new SparkLine(...$this->entries()))->make();

        $this->assertStringContainsString('<svg', $sparkLine);
    }

    /** @test */
    public function test_colors(): void
    {
        $sparkLine = (new SparkLine(...$this->entries()))
            ->withColors('red', 'green', 'blue')
            ->make();

        $this->assertStringContainsString('<stop offset="0%" stop-color="red"></stop>', $sparkLine);
        $this->assertStringContainsString('<stop offset="33%" stop-color="green"></stop>', $sparkLine);
        $this->assertStringContainsString('<stop offset="66%" stop-color="blue"></stop>', $sparkLine);
    }

    /** @test */
    public function test_stroke_width(): void
    {
        $sparkLine = (new SparkLine(...$this->entries()))
            ->withStrokeWidth(50)
            ->make();

        $this->assertStringContainsString('stroke-width="50"', $sparkLine);
    }

    /** @test */
    public function test_dimensions(): void
    {
        $sparkLine = (new SparkLine(...$this->entries()))
            ->withDimensions(500, 501)
            ->make();

        $this->assertStringContainsString('width="500"', $sparkLine);
        $this->assertStringContainsString('height="501"', $sparkLine);
    }

    /** @test */
    public function test_get_total(): void
    {
        $sparkLine = (new SparkLine(...$this->entries()));

        $this->assertEquals(3, $sparkLine->getTotal());
    }
}
