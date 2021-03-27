<?php
declare(strict_types=1);

namespace Emnlmn\Phiode\d;

use Widmogrod\Monad\Either;

/**
 * @template T
 * @template D
 * @template K as key-of<T>
 */
interface Validator
{
    /**
     * @param class-string<T> $a
     * @return mixed
     */
    public function __construct(string $a);
    
    /**
     * @param array $data
     * @return Either\Either
     */
    public function __invoke(array $data): Either\Either;
}