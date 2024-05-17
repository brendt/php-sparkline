<?php

declare(strict_types=1);

namespace Brendt\SparkLine;

use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

final class SparkLine
{
    private Collection $dates;

    private int $maxValue;

    private int $maxItemAmount;

    private int $width = 155;

    private int $height = 30;

    private int $strokeWidth = 2;

    private array $colors = ['#c82161', '#fe2977', '#b848f5', '#b848f5'];

    public static function new(Collection $days, Period $period = Period::DAY, int $precision = 1): self
    {
        return new self($days, new Precision($period, $precision));
    }

    public function __construct(Collection $days, private readonly Precision $precision)
    {
        $this->dates = $days
            ->sortBy(fn (SparkLineInterval $day) => $day->dateTime->getTimestamp())
            ->mapWithKeys(fn (SparkLineInterval $day) => [$this->precision->getKey($day->dateTime) => $day]);

        $this->maxValue = $this->resolveMaxValueFromDays();
        $this->maxItemAmount = $this->resolveMaxItemAmountFromDays();
    }

    public function getTotal(): int
    {
        return $this->dates->sum(fn (SparkLineInterval $day) => $day->count) ?? 0;
    }

    public function getPeriod(): ?\Spatie\Period\Period
    {
        $start = $this->dates->first()?->dateTime;
        $end = $this->dates->last()?->dateTime;

        if (! $start || ! $end) {
            return null;
        }

        return \Spatie\Period\Period::make(
            $start,
            $end,
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

        $clone->maxValue = $maxValue ?? $clone->resolveMaxValueFromDays();

        return $clone;
    }

    public function withMaxItemAmount(?int $maxItemAmount): self
    {
        $clone = clone $this;

        $clone->maxItemAmount = $maxItemAmount ?? $clone->resolveMaxItemAmountFromDays();

        return $clone;
    }

    public function withColors(string ...$colors): self
    {
        $clone = clone $this;

        $clone->colors = $colors;

        return $clone;
    }

    public function make(): string
    {
        $coordinates = $this->resolveCoordinates();
        $colors = $this->resolveColors();
        $width = $this->width;
        $height = $this->height;
        $strokeWidth = $this->strokeWidth;
        $id = Uuid::uuid4()->toString();

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

    private function resolveColors(): array
    {
        $percentageStep = floor(100 / count($this->colors));

        $colorsWithPercentage = [];

        foreach ($this->colors as $i => $color) {
            $colorsWithPercentage[$i * $percentageStep] = $color;
        }

        return $colorsWithPercentage;
    }

    private function resolveMaxValueFromDays(): int
    {
        if ($this->dates->isEmpty()) {
            return 0;
        }

        return $this->dates
            ->sortByDesc(fn (SparkLineInterval $day) => $day->count)
            ->first()
            ->count;
    }

    private function resolveMaxItemAmountFromDays(): int
    {
        return max($this->dates->count(), 1);
    }

    private function resolveCoordinates(): string
    {
        $step = floor($this->width / $this->maxItemAmount);

        return collect(range(0, $this->maxItemAmount))
            ->map(fn (int $range) => ($this->precision->getRangeKey($range)))
            ->reverse()
            ->mapWithKeys(function (string $key) {
                /** @var SparkLineInterval|null $day */
                $day = $this->dates[$key] ?? null;

                return [
                    $key => $day
                        ? $day->rebase($this->height - 5, $this->maxValue)->count
                        : 1, // Default value is 1 because 0 renders too small a line
                ];
            })
            ->values()
            ->map(fn (int $count, int $index) => $index * $step . ',' . $count)
            ->implode(' ');
    }
}
