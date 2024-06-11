<?php

declare(strict_types=1);

namespace Brendt\SparkLine;

use Ramsey\Uuid\Uuid;

final class SparkLine
{
    /** @var \Brendt\SparkLine\SparkLineEntry[] */
    private array $entries;

    private int $maxValue;

    private int $maxItemAmount;

    private int $width = 155;

    private int $height = 30;

    private int $strokeWidth = 2;

    private array $colors;

    private string $id;

    public function __construct(SparkLineEntry|int ...$entries)
    {
        $this->id = Uuid::uuid4()->toString();

        $this->entries = array_map(
            fn (SparkLineEntry|int $entry) => is_int($entry) ? new SparkLineEntry($entry) : $entry,
            $entries
        );

        $this->maxValue = $this->resolveMaxValue($this->entries);
        $this->maxItemAmount = $this->resolveMaxItemAmount($this->entries);
        $this->colors = $this->resolveColors(['#c82161', '#fe2977', '#b848f5', '#b848f5']);
    }

    public function getTotal(): int
    {
        return array_reduce(
            $this->entries,
            fn (int $carry, SparkLineEntry $entry) => $carry + $entry->count,
            0
        );
    }

    public function withStrokeWidth(int $strokeWidth): self
    {
        $clone = clone $this;

        $clone->strokeWidth = $strokeWidth;

        return $clone;
    }

    public function withDimensions(?int $width = null, ?int $height = null): self
    {
        $clone = clone $this;

        $clone->width = $width ?? $clone->width;
        $clone->height = $height ?? $clone->height;

        return $clone;
    }

    public function withMaxValue(?int $maxValue): self
    {
        $clone = clone $this;

        $clone->maxValue = $maxValue ?? $clone->resolveMaxValue($this->entries);

        return $clone;
    }

    public function withMaxItemAmount(?int $maxItemAmount): self
    {
        $clone = clone $this;

        $clone->maxItemAmount = $maxItemAmount ?? $clone->resolveMaxItemAmount($this->entries);

        return $clone;
    }

    public function withColors(string ...$colors): self
    {
        $clone = clone $this;

        $clone->colors = $this->resolveColors($colors);

        return $clone;
    }

    public function make(): string
    {
        ob_start();

        include __DIR__ . '/sparkLine.view.php';

        $svg = ob_get_contents();

        ob_end_clean();

        return $svg;
    }

    public function __toString(): string
    {
        return $this->make();
    }

    public function getCoordinates(): string
    {
        $divider = min($this->width, $this->maxItemAmount);

        $step = floor($this->width / $divider);

        $coordinates = [];

        foreach ($this->entries as $index => $entry) {
            $coordinates[] = $index * $step . ',' . $entry->rebase($this->height - 5, $this->maxValue)->count;
        }

        return implode(' ', $coordinates);
    }

    private function resolveColors(array $colors): array
    {
        $percentageStep = floor(100 / count($colors));

        $colorsWithPercentage = [];

        foreach ($colors as $i => $color) {
            $colorsWithPercentage[$i * $percentageStep] = $color;
        }

        return $colorsWithPercentage;
    }

    private function resolveMaxValue(array $entries): int
    {
        if ($entries === []) {
            return 0;
        }

        usort($entries, fn (SparkLineEntry $a, SparkLineEntry $b) => $a->count <=> $b->count);

        return $entries[array_key_last($entries)]->count;
    }

    private function resolveMaxItemAmount(array $entries): int
    {
        return max(count($entries), 1);
    }
}
