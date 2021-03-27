<?php

declare(strict_types=1);

namespace Emnlmn\Phiode;

use Emnlmn\Phiode\D\Decoder;
use Emnlmn\Phiode\I\Decoder as DecoderI;

/**
 * @template T
 * @param class-string<T> $a
 * @return Decoder
 */
function decode(string $a): Decoder
{
    return new DecoderI($a);
}
