<?php

declare(strict_types=1);

namespace Emnlmn\Phiode;

use Emnlmn\Phiode\Helpers\TestEntity;
use Emnlmn\Phiode\I\Decoder;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertInstanceOf;
use function PHPUnit\Framework\assertIsArray;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Widmogrod\Monad\Either;

/**
 * @covers \Emnlmn\Phiode\I\Decoder
 */
class ValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function test_valid_class(): void
    {
        $res = pipe(
            ['b' => 'val', 'a' => 1],
            (new Decoder(TestEntity::class))
                ->withRule('a', static fn ($a): bool => true)
                ->withRule('b', static fn ($a): bool => true)
        );

        assertInstanceOf(Either\Right::class, $res);
        assertInstanceOf(TestEntity::class, $res->extract());

        /** @var TestEntity $entity */
        $entity = $res->extract();
        self::assertEquals(1, $entity->getA());
        self::assertEquals('val', $entity->getB());
    }

    public function test_unsorted_param(): void
    {
        $decoder = decode(TestEntity::class)
            ->withRule('a', static fn ($a): bool => true)
            ->withRule('b', static fn ($a): bool => true);

        $res = pipe(
            ['a' => 1, 'b' => 'val'],
            $decoder
        );

        assertInstanceOf(Either\Right::class, $res);
        assertInstanceOf(TestEntity::class, $res->extract());

        /** @var TestEntity $entity */
        $entity = $res->extract();
        self::assertEquals(1, $entity->getA());
        self::assertEquals('val', $entity->getB());
    }

    public function test_should_return_errors(): void
    {
        $decoder = decode(TestEntity::class)
            ->withRule('a', static fn ($a): bool => false)
            ->withRule('b', static fn ($a): bool => false);

        $res = pipe(
            ['a' => 1, 'b' => 'val'],
            $decoder,
        );

        assertInstanceOf(Either\Left::class, $res);
        assertIsArray($res->extract());
        assertEquals(['error', 'error'], $res->extract());
    }
}
