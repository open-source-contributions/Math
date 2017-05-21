<?php
declare(strict_types = 1);

namespace Innmind\Math;

use function Innmind\Math\{
    numerize,
    add,
    absolute,
    ceil,
    cosine,
    divide,
    factorial,
    floor,
    frequence,
    mean,
    median,
    modulo,
    multiply,
    power,
    round,
    sine,
    squareRoot,
    subtract,
    tangent,
    max,
    min,
    arcCosine,
    exponential,
    binaryLogarithm,
    naturalLogarithm,
    logarithm,
    commonLogarithm,
    signum,
    arcSine,
    arcTangent
};
use Innmind\Math\{
    Algebra\NumberInterface,
    Algebra\Number,
    Algebra\Addition,
    Algebra\Absolute,
    Algebra\Ceil,
    Algebra\Division,
    Algebra\Floor,
    Algebra\Integer,
    Algebra\Modulo,
    Algebra\Multiplication,
    Algebra\Power,
    Algebra\Round,
    Algebra\SquareRoot,
    Algebra\Subtraction,
    Algebra\Exponential,
    Algebra\BinaryLogarithm,
    Algebra\NaturalLogarithm,
    Algebra\CommonLogarithm,
    Algebra\Signum,
    Geometry\Angle\Degree,
    Geometry\Angle\Radian,
    Geometry\Trigonometry\ArcCosine,
    Geometry\Trigonometry\ArcSine,
    Geometry\Trigonometry\ArcTangent,
    Statistics\Frequence,
    Statistics\Mean,
    Statistics\Median,
    Statistics\Scope
};
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function testNumerize()
    {
        $numbers = numerize(
            1,
            4.2,
            $zero = new Number(0)
        );

        $this->assertCount(3, $numbers);
        $this->assertInstanceOf(NumberInterface::class, $numbers[0]);
        $this->assertInstanceOf(NumberInterface::class, $numbers[1]);
        $this->assertSame($zero, $numbers[2]);
        $this->assertSame(1, $numbers[0]->value());
        $this->assertSame(4.2, $numbers[1]->value());
    }

    public function testAdd()
    {
        $addition = add(1, 4.2, new Number(0));

        $this->assertInstanceOf(Addition::class, $addition);
        $this->assertSame(5.2, $addition->value());
    }

    public function testAbsolute()
    {
        $abs = absolute(-4);

        $this->assertInstanceOf(Absolute::class, $abs);
        $this->assertSame(4, $abs->value());
    }

    public function testCeil()
    {
        $ceil = ceil(4.2);

        $this->assertInstanceOf(Ceil::class, $ceil);
        $this->assertSame(5.0, $ceil->value());
    }

    public function testDivide()
    {
        $division = divide(8, 2);

        $this->assertInstanceOf(Division::class, $division);
        $this->assertSame(4, $division->value());
    }

    public function testFloor()
    {
        $floor = floor(4.2);

        $this->assertInstanceOf(Floor::class, $floor);
        $this->assertSame(4.0, $floor->value());
    }

    public function testModulo()
    {
        $modulo = modulo(8, 1.5);

        $this->assertInstanceOf(Modulo::class, $modulo);
        $this->assertSame(0.5, $modulo->value());
    }

    public function testMultiply()
    {
        $multiplication = multiply(4, 2);

        $this->assertInstanceOf(Multiplication::class, $multiplication);
        $this->assertSame(8, $multiplication->value());
    }

    public function testPower()
    {
        $power = power(4, 2);

        $this->assertInstanceOf(Power::class, $power);
        $this->assertSame(16, $power->value());
    }

    public function testRound()
    {
        $round = round(4.85, 1, 'down');

        $this->assertInstanceOf(Round::class, $round);
        $this->assertSame(4.8, $round->value());
    }

    public function testSquareRoot()
    {
        $squareRoot = squareRoot(4);

        $this->assertInstanceOf(SquareRoot::class, $squareRoot);
        $this->assertSame(2.0, $squareRoot->value());
    }

    public function testSubtract()
    {
        $subtraction = subtract(4, 2, 1);

        $this->assertInstanceOf(Subtraction::class, $subtraction);
        $this->assertSame(1, $subtraction->value());
    }

    /**
     * @dataProvider cosines
     */
    public function testCosine($expected, $number)
    {
        $cos = cosine($number);

        $this->assertInstanceOf(NumberInterface::class, $cos);
        $this->assertSame($expected->value(), $cos->value());
    }

    public function testArcCosine()
    {
        $acos = arcCosine(cosine(30));

        $this->assertInstanceOf(ArcCosine::class, $acos);
        $this->assertSame(30.0, $acos->value());
    }

    public function testArcSine()
    {
        $asin = arcSine(sine(30));

        $this->assertInstanceOf(ArcSine::class, $asin);
        $this->assertSame(30.0, $asin->value());
    }

    public function testArcTangent()
    {
        $atan = arcTangent(tangent(30));

        $this->assertInstanceOf(ArcTangent::class, $atan);
        $this->assertSame(30.0, $atan->value());
    }

    /**
     * @dataProvider sines
     */
    public function testSine($expected, $number)
    {
        $sin = sine($number);

        $this->assertInstanceOf(NumberInterface::class, $sin);
        $this->assertSame($expected->value(), $sin->value());
    }

    /**
     * @dataProvider tangents
     */
    public function testTangent($number)
    {
        $tan = tangent($number);

        $this->assertInstanceOf(NumberInterface::class, $tan);
        $this->assertSame(
            divide(sine($number), cosine($number))->value(),
            $tan->value()
        );
    }

    public function testFrequence()
    {
        $frequence = frequence(1, 1, 2, 3, 4, 4);

        $this->assertInstanceOf(Frequence::class, $frequence);
        $this->assertSame(
            divide(2, 6)->value(),
            $frequence(new Number(1))->value()
        );
        $this->assertSame(
            divide(2, 6)->value(),
            $frequence(new Number(4))->value()
        );
        $this->assertSame(
            divide(1, 6)->value(),
            $frequence(new Number(2))->value()
        );
        $this->assertSame(
            divide(1, 6)->value(),
            $frequence(new Number(3))->value()
        );
    }

    public function testMean()
    {
        $mean = mean(1, 2, 2, 2, 3, 5, 5, 6, 6, 7);

        $this->assertInstanceOf(NumberInterface::class, $mean);
        $this->assertSame(3.9, $mean->value());
    }

    public function testMedian()
    {
        $median = median(1, 2, 2, 2, 3, 5, 5, 6, 6, 7);

        $this->assertInstanceOf(NumberInterface::class, $median);
        $this->assertSame(4, $median->value());
    }

    public function testScope()
    {
        $scope = scope(1, 2, 2, 2, 3, 5, 5, 6, 6, 7);

        $this->assertInstanceOf(NumberInterface::class, $scope);
        $this->assertSame(6, $scope->value());
    }

    public function testFactorial()
    {
        $int = factorial(3);

        $this->assertInstanceOf(NumberInterface::class, $int);
        $this->assertSame(6, $int->value());
    }

    public function testMax()
    {
        $number = max(
            1,
            new Number(2),
            $expected = new Number(4),
            3
        );

        $this->assertSame($expected, $number);
    }

    public function testMin()
    {
        $number = min(
            2,
            $expected = new Number(1),
            new Number(4),
            3
        );

        $this->assertSame($expected, $number);
    }

    public function testExponential()
    {
        $exp = exponential(4);

        $this->assertInstanceOf(Exponential::class, $exp);
        $this->assertSame('e^4', (string) $exp);
    }

    public function testBinaryLogarithm()
    {
        $lb = binaryLogarithm(1);

        $this->assertInstanceOf(BinaryLogarithm::class, $lb);
        $this->assertSame('lb(1)', (string) $lb);
    }

    public function testNaturalLogarithm()
    {
        $ln = naturalLogarithm(1);

        $this->assertInstanceOf(NaturalLogarithm::class, $ln);
        $this->assertSame('ln(1)', (string) $ln);
    }

    public function testLogarithm()
    {
        $ln = logarithm(1);

        $this->assertInstanceOf(NaturalLogarithm::class, $ln);
        $this->assertSame('ln(1)', (string) $ln);
    }

    public function testCommonLogarithm()
    {
        $lg = commonLogarithm(1);

        $this->assertInstanceOf(CommonLogarithm::class, $lg);
        $this->assertSame('lg(1)', (string) $lg);
    }

    public function testSignum()
    {
        $sgn = signum(1);

        $this->assertInstanceOf(Signum::class, $sgn);
        $this->assertSame('sgn(1)', (string) $sgn);
    }

    public function cosines(): array
    {
        return [
            [divide(squareRoot(3), 2), 30],
            [divide(squareRoot(3), 2), 30.0],
            [divide(squareRoot(3), 2), new Number(30)],
            [divide(squareRoot(3), 2), new Degree(new Number(30))],
            [divide(squareRoot(3), 2), (new Degree(new Number(30)))->toRadian()],
        ];
    }

    public function sines(): array
    {
        return [
            [divide(squareRoot(3), 2), 60],
            [divide(squareRoot(3), 2), 60.0],
            [divide(squareRoot(3), 2), new Number(60)],
            [divide(squareRoot(3), 2), new Degree(new Number(60))],
            [divide(squareRoot(3), 2), (new Degree(new Number(60)))->toRadian()],
            [new Number(0.5), 30],
            [new Number(0.5), 30.0],
            [new Number(0.5), new Number(30)],
            [new Number(0.5), new Degree(new Number(30))],
            [new Number(0.5), (new Degree(new Number(30)))->toRadian()],
        ];
    }

    public function tangents(): array
    {
        return [
            [0],
            [30],
            [45],
            [60],
            [90],
        ];
    }
}
