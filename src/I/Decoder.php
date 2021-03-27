<?php

declare(strict_types=1);

namespace Emnlmn\Phiode\I;

use Emnlmn\Phiode\D\Decoder as DecoderD;
use ReflectionException;
use Widmogrod\Monad\Either\Either;
use function Widmogrod\Monad\Either\left;
use function Widmogrod\Monad\Either\right;

/**
 * @template T of object
 * @template D of array<string, mixed>
 * @template K of key-of<D>
 * @template P of callable(D[K]): bool
 * @template R of array<K, P>
 *
 * @implements DecoderD<T, D, K, P, R>
 */
final class Decoder implements DecoderD
{
    /**
     * @var class-string<T>
     */
    private string $targetClass;

    /**
     * @var R
     */
    private array $validationRules;
    
    /**
     * @param class-string<T> $targetClass
     */
    public function __construct(string $targetClass)
    {
        $this->targetClass = $targetClass;
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->validationRules = [];
    }
    
    public function withRule(string $key, callable $predicate): self
    {
        $this->validationRules[$key] = $predicate;

        return $this;
    }

    public function getRules(): array
    {
        return $this->validationRules;
    }

    public function __invoke(array $data): Either
    {
        $errors = $this->validate($data);

        try {
            return $errors
                ? left($errors)
                : right($this->instantiateClass($data));
        } catch (ReflectionException $exception) {
            return left([$exception->getMessage()]);
        }
    }

    /**
     * @param D $data
     *
     * @return array
     */
    private function validate(array $data): array
    {
        $errors = [];

        /**
         * @var K $key
         * @var P $predicate
         */
        foreach ($this->getRules() as $key => $predicate) {
            $errors[] = $predicate($data[$key]) ? null : 'error';
        }

        return \array_filter($errors);
    }

    /**
     * @param D $data
     *
     * @throws ReflectionException
     *
     * @return T
     */
    private function instantiateClass(array $data)
    {
        $reflectionClass = new \ReflectionClass($this->targetClass);

        $ctor = $reflectionClass->getConstructor();

        if (null === $ctor) {
            /** @psalm-suppress all */
            return new $this->targetClass();
        }

        $paramNames = \array_flip(\array_map(static fn (\ReflectionParameter $p) => $p->name, $ctor->getParameters()));

        $args = \array_replace($paramNames, $data);

        /**
         * @var T $i
         * @psalm-suppress MixedArgumentTypeCoercion
         */
        $i = $reflectionClass->newInstanceArgs($args);

        return $i;
    }
}
