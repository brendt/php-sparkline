<?php
/** @var \Brendt\SparkLine\SparkLine $this */
?>

<svg width="<?= $this->width ?>" height="<?= $this->height ?>">
    <defs>
        <linearGradient id="gradient-<?= $this->id ?>" x1="0" x2="0" y1="1" y2="0">
            <?php
                foreach ($this->colors as $percentage => $color) {
                    echo <<<HTML
                    <stop offset="{$percentage}%" stop-color="{$color}"></stop>
                    HTML;

                    echo PHP_EOL;
                }
?>
        </linearGradient>
        <mask id="sparkline-<?= $this->id ?>" x="0" y="0" width="<?= $this->width ?>" height="<?= $this->height - 2 ?>">
            <polyline
                transform="translate(0, <?= $this->height - 2 ?>) scale(1,-1)"
                points="<?= $this->getCoordinates() ?>"
                fill="transparent"
                stroke="<?= $this->colors[0] ?>"
                stroke-width="<?= $this->strokeWidth ?>"
            >
            </polyline>
        </mask>
    </defs>

    <g transform="translate(0, 0)">
        <rect x="0" y="0" width="<?= $this->width ?>" height="<?= $this->height ?>" style="stroke: none; fill: url(#gradient-<?= $this->id ?>); mask: url(#sparkline-<?= $this->id ?>)"></rect>
    </g>
</svg>
