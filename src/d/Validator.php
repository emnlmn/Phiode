<?php

declare(strict_types=1);

namespace Emnlmn\Phiode\d;

use Widmogrod\Monad\Either\Either;

/**
 * @template T
 * @template D
 * @template K as key-of<T>
 * @template P as callable(D[K]): bool
 */
interface Validator
{
    /**
     * @param class-string<T> $targetClass
     */
    public function __construct(string $targetClass);

    /**
     * @param D $data
     *
     * @return Either
     */
    public function __invoke(array $data): Either;

    /**
     * @param K $key
     * @param P $predicate
     *
     * @return self
     */
    public function withRule(string $key, callable $predicate): self;
}
