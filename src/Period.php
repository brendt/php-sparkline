<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: Jozef Môstka
 * Date: 17. 5. 2024
 * Time: 19:59
 */

namespace Brendt\SparkLine;

enum Period: int
{
    case MONTH = 2678400;
    case DAY = 86400;
    case HOUR = 3600;
    case MINUTE = 60;
}
