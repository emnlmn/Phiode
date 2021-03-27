<?php
declare(strict_types=1);

namespace Emnlmn\Phiode;

/**
 * @covers pipe
 */
class PipeTest extends \PHPUnit\Framework\TestCase
{
    public function test_pipe_one_arg(): void
    {
        $a = 0;
        $callback = static fn (int $val): int => $val + 1;

        self::assertSame(1, pipe($a, $callback));
    }
    
    public function test_pipe_two_arg(): void
    {
        $a = 0;
        $c1 = static fn (int $val): int => $val + 1;
        $c2 = static fn (int $val): string => "value is $val";
        
        $result = pipe($a, $c1, $c2);
        
        self::assertEquals('value is 1', $result);
    }
    
    public function test_pipe_three_arg(): void {
        $a = 0;
        $c1 = static fn (int $val): int => $val + 1;
        $c2 = static fn (int $val): int => $val + 2;
        $c3 = static fn (int $val): string => "value is $val";
    
        $result = pipe($a, $c1, $c2, $c3);
    
        self::assertEquals('value is 3', $result);
    }
    
    public function test_pipe_four_arg(): void {
        $a = 0;
        $c1 = static fn (int $val): int => $val + 1;
        $c2 = static fn (int $val): int => $val + 2;
        $c3 = static fn (int $val): int => $val + 3;
        $c4 = static fn (int $val): string => "value is $val";
        
        $result = pipe($a, $c1, $c2, $c3, $c4);
        
        self::assertEquals('value is 6', $result);
    }
    
    public function test_pipe_five_arg(): void {
        $a = 0;
        $c1 = static fn (int $val): int => $val + 1;
        $c2 = static fn (int $val): int => $val + 2;
        $c3 = static fn (int $val): int => $val + 3;
        $c4 = static fn (int $val): int => $val + 4;
        $c5 = static fn (int $val): string => "value is $val";
        
        $result = pipe($a, $c1, $c2, $c3, $c4, $c5);
        
        self::assertEquals('value is 10', $result);
    }
    
    public function test_pipe_six_arg(): void {
        $a = 0;
        $c1 = static fn (int $val): int => $val + 1;
        $c2 = static fn (int $val): int => $val + 2;
        $c3 = static fn (int $val): int => $val + 3;
        $c4 = static fn (int $val): int => $val + 4;
        $c5 = static fn (int $val): int => $val + 5;
        $c6 = static fn (int $val): string => "value is $val";
        
        $result = pipe($a, $c1, $c2, $c3, $c4, $c5, $c6);
        
        self::assertEquals('value is 15', $result);
    }
}