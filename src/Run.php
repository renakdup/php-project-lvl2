<?php

declare(strict_types=1);

namespace CalcDiff\Gendiff;

use Docopt;

const DOCOPT_VERSION = '0.1';

const DOC = <<<DOC
Generate diff

Usage:
  gendiff (-h|--help)
  gendiff (-v|--version)
  gendiff [--format <fmt>] <firstFile> <secondFile>

Options:
  -h --help                     Show this screen
  -v --version                  Show version
  --format <fmt>                Report format [default: tree]
DOC;


function run(): void
{
    $args = Docopt::handle(DOC, [
        'version' => DOCOPT_VERSION,
    ]);

    $filePathBefore = getFilePath($args['<firstFile>']);
    $filePathAfter = getFilePath($args['<secondFile>']);

    $format = $args['--format'];

    echo gendiff($filePathBefore, $filePathAfter, $format);
}

function getFilePath(string $filePath): string
{
    $isAbsolutePath = function (string $str): bool {
        return strpos($str, '/') === 0;
    };

    return $isAbsolutePath($filePath) ? $filePath : getcwd() . '/' . $filePath;
}
