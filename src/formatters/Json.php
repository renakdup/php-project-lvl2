<?php

declare(strict_types=1);

namespace CalcDiff\formatters\Json;

function render(array $astDiff): string
{
    return json_encode($astDiff);
}
