<?php

use Brendt\SparkLine\SparkLine;

require_once __DIR__ . '/../vendor/autoload.php';

$sparkline = (new SparkLine(
    ...array_map(
        fn () => rand(1, 5),
        range(1, 1000)
    )
))
    ->withStrokeWidth(1)
    ->withDimensions(width: 1000, height: 200);

?>

<html>
<body>
<?= $sparkline ?>
</body>
</html>
