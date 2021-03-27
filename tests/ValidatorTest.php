<?php

declare(strict_types=1);

namespace Emnlmn\Phiode;

use Emnlmn\Phiode\i\Validator;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsArray;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Widmogrod\Monad\Either;

class Entity
{
    private int $a;

    private string $b;

    public function __construct(int $a, string $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    public function getA(): int
    {
        return $this->a;
    }

    public function getB(): string
    {
        return $this->b;
    }
}

/**
 * @covers \Emnlmn\Phiode\i\Validator
 */
class ValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function test_valid_class(): void
    {
        $res = pipe(
            ['b' => 'val', 'a' => 1],
            (new Validator(Entity::class))
                ->withRule('a', static fn ($a): bool => true)
                ->withRule('b', static fn ($a): bool => true)
        );

        assertInstanceOf(Either\Right::class, $res);
        assertInstanceOf(Entity::class, $res->extract());

        /** @var Entity $entity */
        $entity = $res->extract();
        self::assertEquals(1, $entity->getA());
        self::assertEquals('val', $entity->getB());
    }

    public function test_unsorted_param(): void
    {
        $res = pipe(
            ['a' => 1, 'b' => 'val'],
            (new Validator(Entity::class))
                ->withRule('a', static fn ($a): bool => true)
                ->withRule('b', static fn ($a): bool => true)
        );

        assertInstanceOf(Either\Right::class, $res);
        assertInstanceOf(Entity::class, $res->extract());

        /** @var Entity $entity */
        $entity = $res->extract();
        self::assertEquals(1, $entity->getA());
        self::assertEquals('val', $entity->getB());
    }

    public function test_should_return_errors(): void
    {
        $validator = (new Validator(Entity::class))
            ->withRule('a', static fn ($a): bool => false)
            ->withRule('b', static fn ($a): bool => false);

        $res = pipe(
            ['a' => 1, 'b' => 'val'],
            $validator,
        );

        assertInstanceOf(Either\Left::class, $res);
        assertIsArray($res->extract());
        assertEquals(['error', 'error'], $res->extract());
    }
}
