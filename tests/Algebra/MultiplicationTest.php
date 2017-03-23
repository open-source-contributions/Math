<?php
declare(strict_types = 1);

namespace Tests\Innmind\Math\Algebra;

use Innmind\Math\Algebra\{
    Multiplication,
    Number,
    OperationInterface,
    NumberInterface,
    Addition
};
use PHPUnit\Framework\TestCase;

class MultiplicationTest extends TestCase
{
    public function testInterface()
    {
        $multiplication = new Multiplication(
            new Number(4),
            new Number(42)
        );

        $this->assertInstanceOf(OperationInterface::class, $multiplication);
        $this->assertInstanceOf(NumberInterface::class, $multiplication);
    }

    public function testResult()
    {
        $multiplication = new Multiplication(
            new Number(42),
            new Number(24)
        );
        $result = $multiplication->result();

        $this->assertInstanceOf(Number::class, $result);
        $this->assertSame(1008, $result->value());
    }

    public function testValue()
    {
        $multiplication = new Multiplication(
            new Number(4),
            new Number(2)
        );

        $this->assertSame(8, $multiplication->value());
    }

    public function testEquals()
    {
        $multiplication = new Multiplication(
            new Number(4),
            new Number(2)
        );

        $this->assertTrue($multiplication->equals(new Number(8)));
        $this->assertFalse($multiplication->equals(new Number(8.1)));
    }

    public function testHigherThan()
    {
        $multiplication = new Multiplication(
            new Number(4),
            new Number(2)
        );

        $this->assertFalse($multiplication->higherThan(new Number(8)));
        $this->assertTrue($multiplication->higherThan(new Number(7.9)));
    }

    public function testStringCast()
    {
        $multiplication = new Multiplication(
            new Addition(
                new Number(12),
                new Number(12)
            ),
            new Number(42),
            new Number(66)
        );

        $this->assertSame('(12 + 12) x 42 x 66', (string) $multiplication);
    }

    /**
     * @expectedException Innmind\Math\Exception\OperationMustContainAtLeastTwoNumbersException
     */
    public function testThrowWhenNotEnoughNumbers()
    {
        new Multiplication(new Number(42));
    }
}
