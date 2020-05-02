<?php

declare(strict_types=1);

namespace Renakdup\inc\CommandLine;

use Docopt;

use function Renakdup\Gendiff\genDiff;

const ARG_FIRST_FILE_1 = '<firstFile>';
const ARG_FIRST_FILE_2 = '<secondFile>';

const ARG_FORMAT = '--format';
const FORMAT_JSON = 'json';
const FORMAT_PLAIN = 'plain';

const DOC = "Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [" . ARG_FORMAT . " <fmt>]  " . ARG_FIRST_FILE_1 . " " . ARG_FIRST_FILE_2 . "

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  " . ARG_FORMAT . " <fmt>                Report format [default: " . FORMAT_JSON . "]";

function setup(): void
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

        $format = $args[ARG_FORMAT];

        echo genDiff($file1, $file2, $format);
        return;
    }

    echo getCommandInfo($args);
}

function getCommandInfo($args): string
{
    $result = '';

    foreach ($args as $k => $v) {
        $result .= $k . ': ' . json_encode($v) . PHP_EOL;
    }

    return $result;
}
