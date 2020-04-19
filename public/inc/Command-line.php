<?php

declare(strict_types=1);

namespace Renakdup\inc\Command_line;

use Docopt;
use function Renakdup\Gendiff\genDiff;

const ARG_FIRST_FILE_1 = '<firstFile>';
const ARG_FIRST_FILE_2 = '<secondFile>';

const DOC = "Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>]  " . ARG_FIRST_FILE_1 . " " . ARG_FIRST_FILE_2 . "

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: pretty]";

function setup(): string
{
    $args = Docopt::handle(DOC, [
        'version' => '0.1',
    ]);

    $isAbsolutePath = function (string $str): bool {
        return strpos($str, '/') === 0;
    };

    if (isset($args[ARG_FIRST_FILE_1]) && isset($args[ARG_FIRST_FILE_2])) {
        $file1 = $args[ARG_FIRST_FILE_1];
        $file2 = $args[ARG_FIRST_FILE_2];

        $file1 = $isAbsolutePath($file1) ? $file1 : getcwd() . '/' . $file1;
        $file2 = $isAbsolutePath($file2) ? $file2 : getcwd() . '/' . $file2;

        return genDiff($file1, $file2);
    }

    ob_start();
    foreach ($args as $k => $v) {
        echo $k . ': ' . json_encode($v) . PHP_EOL;
    }

    return ob_get_clean();
}
