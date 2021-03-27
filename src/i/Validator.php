<?php

declare(strict_types=1);

namespace Emnlmn\Phiode\i;

use Emnlmn\Phiode\d\Validator as ValidatorD;
use ReflectionException;
use Widmogrod\Monad\Either\Either;
use function Widmogrod\Monad\Either\left;
use function Widmogrod\Monad\Either\right;

/**
 * @template T
 * @template D
 * @template K as key-of<D>
 * @template P as callable(D[K]): bool
 */
class Validator implements ValidatorD
{
    /**
     * @var class-string<T>
     */
    private string $targetClass;

    /**
     * @psalm-var array<K, P> $validationRules
     *
     * @var array<string, callable>
     */
    private array $validationRules = [];

    public function __construct(string $targetClass)
    {
        $this->targetClass = $targetClass;
    }

    /**
     * @param K $key
     * @param P $predicate
     *
     * @return self
     */
    public function withRule(string $key, callable $predicate): self
    {
        $this->validationRules[$key] = $predicate;

        return $this;
    }

    /**
     * @param D $data
     *
     * @throws ReflectionException
     *
     * @return Either
     */
    public function __invoke(array $data): Either
    {
        $errors = $this->validate($data);

        return $errors
            ? left($errors)
            : right($this->instantiateClass($data));
    }

    /**
     * @param D $data
     *
     * @return array<array-key, string>
     */
    private function validate(array $data): array
    {
        $errors = [];

        foreach ($this->validationRules as $key => $validationRule) {
            $errors[] = $validationRule($data[$key]) ? null : 'error';
        }

        return array_filter($errors);
    }

    /**
     * @param D $data
     *
     * @throws ReflectionException
     *
     * @return T
     */
    private function instantiateClass(array $data): object
    {
        $reflectionClass = new \ReflectionClass($this->targetClass);

        $ctor = $reflectionClass->getConstructor();

        if (null === $ctor) {
            return new $this->targetClass();
        }

        $paramNames = array_flip(array_map(static fn (\ReflectionParameter $p) => $p->name, $ctor->getParameters()));

        $args = array_replace($paramNames, $data);

        /** @var T $i */
        $i = $reflectionClass->newInstanceArgs($args);

        return $i;
    }
}
