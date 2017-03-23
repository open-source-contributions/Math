<?php
declare(strict_types = 1);

namespace Tests\Innmind\Math\Algebra;

use Innmind\Math\Algebra\{
    Division,
    Number,
    OperationInterface
};
use PHPUnit\Framework\TestCase;

class DivisionTest extends TestCase
{
    public function testInterface()
    {
        $division = new Division(
            $dividend = new Number(4),
            $divisor = new Number(2)
        );

        $this->assertInstanceOf(OperationInterface::class, $division);
        $this->assertSame($dividend, $division->dividend());
        $this->assertSame($divisor, $division->divisor());
    }

    public function testResult()
    {
        $division = new Division(new Number(4), new Number(2));
        $result = $division->result();

        $this->assertInstanceOf(Number::class, $result);
        $this->assertSame(2, $result->value());
    }

    public function testStringCast()
    {
        $this->assertSame(
            '4 ÷ 2',
            (string) new Division(new Number(4), new Number(2))
        );
    }

    /**
     * @expectedException Innmind\Math\Exception\DivisionByZeroError
     */
    public function testThrowWhenTryingToDivideByZero()
    {
        new Division(new Number(4), new Number(0));
    }
}
