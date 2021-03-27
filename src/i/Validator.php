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
    private string $targetClass;
    
    /**
     * @psalm-var array<K, P> $validationRules
     * @var array<string, callable> $validationRules
     */
    private array $validationRules = [];
    
    public function __construct(string $a)
    {
        $this->targetClass = $a;
    }
    
    /**
     * @param K $key
     * @param P $predicate
     * @return self
     */
    public function withRule(string $key, callable $predicate): self
    {
        $this->validationRules[$key] = $predicate;
        
        return $this;
    }
    
    /**
     * @param D $data
     * @return Either
     * @throws ReflectionException
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
     * @return array<array-key, string>
     */
    private function validate(array $data): array
    {
        $errors = [];
        
        foreach ($this->validationRules as $key => $validationRule) {
            $errors = $validationRule($data[$key]) ? 'error' : null;
        }
        
        return array_unique($errors);
    }
    
    /**
     * @param D $data
     * @return T
     *
     * @throws ReflectionException
     */
    private function instantiateClass(array $data): object
    {
        $reflectionClass = new \ReflectionClass($this->targetClass);
    
        /** @var T $i */
        $i = $reflectionClass->newInstanceArgs($data);
        
        return $i;
    }
}