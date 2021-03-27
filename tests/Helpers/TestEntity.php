<?php

declare(strict_types=1);

namespace Emnlmn\Phiode\Helpers;

class TestEntity
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
