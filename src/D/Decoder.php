<?php

declare(strict_types=1);

namespace Emnlmn\Phiode\D;

use Widmogrod\Monad\Either\Either;

/**
 * @template T of object
 * @template D of array<string, mixed>
 * @template K of key-of<D>
 * @template P of callable(D[K]): bool
 * @template R of array<K, P>
 */
interface Decoder
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

    /**
     * @return R
     */
    public function getRules(): array;
}
