<?php

declare(strict_types=1);

namespace Renakdup\formatters\Json;

function render(array $astDiff): string
{
    $lines = json_encode($astDiff);

    return $lines . PHP_EOL;
}
