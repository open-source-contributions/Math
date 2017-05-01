<?php
declare(strict_types = 1);

namespace Tests\Innmind\Math\Algebra;

use Innmind\Math\Algebra\{
    Integer,
    Number,
    NumberInterface,
    Addition,
    Subtraction,
    Multiplication,
    Division,
    Round,
    Floor,
    Ceil,
    Modulo,
    Absolute,
    Power,
    SquareRoot
};
use PHPUnit\Framework\TestCase;

class IntegerTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            NumberInterface::class,
            new Integer(42)
        );
    }

    public function testInt()
    {
        $number = new Integer(42);

        $this->assertSame(42, $number->value());
        $this->assertSame('42', (string) $number);
    }

    public function testEquals()
    {
        $this->assertTrue((new Integer(42))->equals(new Integer(42)));
        $this->assertTrue((new Integer(42))->equals(new Number(42.0)));
        $this->assertFalse((new Integer(42))->equals(new Number(42.24)));
    }

    public function testHigherThan()
    {
        $this->assertFalse((new Integer(42))->higherThan(new Integer(42)));
        $this->assertTrue((new Integer(42))->higherThan(new Number(41.24)));
    }

    public function testAdd()
    {
        $number = new Integer(42);
        $number = $number->add(new Integer(66));

        $this->assertInstanceOf(Addition::class, $number);
        $this->assertSame(108, $number->value());
    }

    public function testSubtract()
    {
        $number = new Integer(42);
        $number = $number->subtract(new Integer(66));

        $this->assertInstanceOf(Subtraction::class, $number);
        $this->assertSame(-24, $number->value());
    }

    public function testDivideBy()
    {
        $number = new Integer(42);
        $number = $number->divideBy(new Integer(2));

        $this->assertInstanceOf(Division::class, $number);
        $this->assertSame(21, $number->value());
    }

    public function testMulitplyBy()
    {
        $number = new Integer(42);
        $number = $number->multiplyBy(new Integer(2));

        $this->assertInstanceOf(Multiplication::class, $number);
        $this->assertSame(84, $number->value());
    }

    public function testRound()
    {
        $number = new Integer(42);
        $number = $number->round(1);

        $this->assertInstanceOf(Round::class, $number);
        $this->assertSame(42.0, $number->value());
    }

    public function testFloor()
    {
        $number = new Integer(42);
        $number = $number->floor();

        $this->assertInstanceOf(Floor::class, $number);
        $this->assertSame(42.0, $number->value());
    }

    public function testCeil()
    {
        $number = new Integer(42);
        $number = $number->ceil();

        $this->assertInstanceOf(Ceil::class, $number);
        $this->assertSame(42.0, $number->value());
    }

    public function testModulo()
    {
        $number = new Integer(3);
        $number = $number->modulo(new Integer(2));

        $this->assertInstanceOf(Modulo::class, $number);
        $this->assertSame(1.0, $number->value());
    }

    public function testAbsolute()
    {
        $number = new Integer(-9);
        $number = $number->absolute();

        $this->assertInstanceOf(Absolute::class, $number);
        $this->assertSame(9, $number->value());
    }

    public function testPower()
    {
        $number = new Integer(-9);
        $number = $number->power(new Integer(2));

        $this->assertInstanceOf(Power::class, $number);
        $this->assertSame(81, $number->value());
    }

    public function testSquareRoot()
    {
        $number = new Integer(4);
        $number = $number->squareRoot();

        $this->assertInstanceOf(SquareRoot::class, $number);
        $this->assertSame(2.0, $number->value());
    }
}