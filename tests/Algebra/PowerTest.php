<?php
declare(strict_types = 1);

namespace Tests\Innmind\Math\Algebra;

use Innmind\Math\Algebra\{
    Power,
    NumberInterface,
    OperationInterface,
    Number,
    Addition,
    Subtraction,
    Multiplication,
    Division,
    Round,
    Floor,
    Ceil,
    Modulo,
    Absolute
};
use PHPUnit\Framework\TestCase;

class PowerTest extends TestCase
{
    public function testInterface()
    {
        $power = new Power(
            $this->createMock(NumberInterface::class),
            $this->createMock(NumberInterface::class)
        );

        $this->assertInstanceOf(NumberInterface::class, $power);
        $this->assertInstanceOf(OperationInterface::class, $power);
    }

    public function testStringCast()
    {
        $power = new Power(
            new Number(42.24),
            new Number(2.1)
        );

        $this->assertSame('42.24^2.1', (string) $power);
    }

    public function testStringCastOperations()
    {
        $power = new Power(
            new Addition(
                new Number(1),
                new Number(1)
            ),
            new Addition(
                new Number(2),
                new Number(2)
            )
        );

        $this->assertSame('(1 + 1)^(2 + 2)', (string) $power);
    }

    public function testEquals()
    {
        $power = new Power(
            new Number(2),
            new Number(2.1)
        );

        $this->assertTrue($power->equals(new Number(4.2870938501451725)));
        $this->assertFalse($power->equals(new Number(4)));
    }

    public function testHigherThan()
    {
        $power = new Power(
            new Number(2),
            new Number(2.1)
        );

        $this->assertTrue($power->higherThan(new Number(4.28709385)));
        $this->assertFalse($power->higherThan(new Number(4.2870938501451725)));
    }

    public function testAdd()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->add(new Number(66));

        $this->assertInstanceOf(Addition::class, $number);
        $this->assertSame(70, $number->value());
    }

    public function testSubtract()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->subtract(new Number(66));

        $this->assertInstanceOf(Subtraction::class, $number);
        $this->assertSame(-62, $number->value());
    }

    public function testDivideBy()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->divideBy(new Number(2));

        $this->assertInstanceOf(Division::class, $number);
        $this->assertSame(2, $number->value());
    }

    public function testMulitplyBy()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->multiplyBy(new Number(2));

        $this->assertInstanceOf(Multiplication::class, $number);
        $this->assertSame(8, $number->value());
    }

    public function testRound()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->round(1);

        $this->assertInstanceOf(Round::class, $number);
        $this->assertSame(4.0, $number->value());
    }

    public function testFloor()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->floor();

        $this->assertInstanceOf(Floor::class, $number);
        $this->assertSame(4.0, $number->value());
    }

    public function testCeil()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->ceil();

        $this->assertInstanceOf(Ceil::class, $number);
        $this->assertSame(4.0, $number->value());
    }

    public function testModulo()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->modulo(new Number(0.5));

        $this->assertInstanceOf(Modulo::class, $number);
        $this->assertSame(0.0, $number->value());
    }

    public function testAbsolute()
    {
        $power = new Power(
            new Number(-2),
            new Number(3)
        );
        $number = $power->absolute();

        $this->assertInstanceOf(Absolute::class, $number);
        $this->assertSame(8, $number->value());
    }

    public function testPower()
    {
        $power = new Power(
            new Number(2),
            new Number(2)
        );
        $number = $power->power(new Number(2));

        $this->assertInstanceOf(Power::class, $number);
        $this->assertSame(16, $number->value());
    }
}
