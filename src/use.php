<?php
declare(strict_types=1);

namespace Emnlmn\Phiode;

function takeString(int $i): int {
    return $i;
}

$c1 = static fn (int $val): int => 1;
//$c2 = static fn (int $val): string => 'It works';

$res = pipe(1, $c1);

$_ = takeString($res);

