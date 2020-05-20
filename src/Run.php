<?php

declare(strict_types=1);

namespace Renakdup\Gendiff;

use Docopt;

use function Renakdup\Gendiff\gendiff;
use function Renakdup\ParseFile\parseFile;

const DOCOPT_VERSION = '0.1';

const ARG_FIRST_FILE_1 = '<firstFile>';
const ARG_FIRST_FILE_2 = '<secondFile>';

const ARG_FORMAT = '--format';

const FORMAT_DEFAULT = 'default';
const FORMAT_PLAIN = 'plain';
const FORMAT_JSON = 'json';

const DOC = "Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [" . ARG_FORMAT . " <fmt>]  " . ARG_FIRST_FILE_1 . " " . ARG_FIRST_FILE_2 . "

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  " . ARG_FORMAT . " <fmt>                Report format [default: " . FORMAT_DEFAULT . "]";


function run(): void
{
    $args = Docopt::handle(DOC, [
        'version' => DOCOPT_VERSION,
    ]);

    if (isset($args[ARG_FIRST_FILE_1]) && isset($args[ARG_FIRST_FILE_2])) {
        $filePathBefore = getFilePath($args[ARG_FIRST_FILE_1]);
        $filePathAfter = getFilePath($args[ARG_FIRST_FILE_2]);

        $format = $args[ARG_FORMAT];

        echo gendiff($filePathBefore, $filePathAfter, $format);
        return;
    }

    echo getCommandInfo($args);
}

function getFilePath(string $filePath): string
{
    $isAbsolutePath = function (string $str): bool {
        return strpos($str, '/') === 0;
    };

    return $isAbsolutePath($filePath) ? $filePath : getcwd() . '/' . $filePath;
}

function getCommandInfo($args): string
{
    return collect($args)
        ->map(function ($val, $key) {
            return $key . ': ' . json_encode($val) . PHP_EOL;
        })
        ->implode(PHP_EOL);
}
