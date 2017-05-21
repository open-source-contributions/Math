<?php
declare(strict_types = 1);

namespace Innmind\Math\Algebra;

use Innmind\Math\{
    Algebra\Number\Infinite,
    DefinitionSet\SetInterface,
    DefinitionSet\Range,
    Exception\OutOfDefinitionSetException
};

/**
 * Base 10 logarithm
 */
final class CommonLogarithm implements OperationInterface, NumberInterface
{
    private static $definitionSet;

    private $number;
    private $result;

    public function __construct(NumberInterface $number)
    {
        if (!self::definitionSet()->contains($number)) {
            throw new OutOfDefinitionSetException;
        }

        $this->number = $number;
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->result()->value();
    }

    public function equals(NumberInterface $number): bool
    {
        return $this->result()->equals($number);
    }

    public function higherThan(NumberInterface $number): bool
    {
        return $this->result()->higherThan($number);
    }

    public function add(
        NumberInterface $number,
        NumberInterface ...$numbers
    ): NumberInterface {
        return new Addition($this, $number, ...$numbers);
    }

    public function subtract(
        NumberInterface $number,
        NumberInterface ...$numbers
    ): NumberInterface {
        return new Subtraction($this, $number, ...$numbers);
    }

    public function divideBy(NumberInterface $number): NumberInterface
    {
        return new Division($this, $number);
    }

    public function multiplyBy(
        NumberInterface $number,
        NumberInterface ...$numbers
    ): NumberInterface {
        return new Multiplication($this, $number, ...$numbers);
    }

    public function round(int $precision = 0, string $mode = Round::UP): NumberInterface
    {
        return new Round($this, $precision, $mode);
    }

    public function floor(): NumberInterface
    {
        return new Floor($this);
    }

    public function ceil(): NumberInterface
    {
        return new Ceil($this);
    }

    public function modulo(NumberInterface $modulus): NumberInterface
    {
        return new Modulo($this, $modulus);
    }

    public function absolute(): NumberInterface
    {
        return new Absolute($this);
    }

    public function power(NumberInterface $power): NumberInterface
    {
        return new Power($this, $power);
    }

    public function squareRoot(): NumberInterface
    {
        return new SquareRoot($this);
    }

    public function exponential(): NumberInterface
    {
        return new Exponential($this);
    }

    public function binaryLogarithm(): NumberInterface
    {
        return new BinaryLogarithm($this);
    }

    public function naturalLogarithm(): NumberInterface
    {
        return new NaturalLogarithm($this);
    }

    public function commonLogarithm(): NumberInterface
    {
        return new self($this);
    }

    public function signum(): NumberInterface
    {
        return new Signum($this);
    }

    public function result(): NumberInterface
    {
        return $this->result ?? $this->result = Number::wrap(
            log10($this->number->value())
        );
    }

    public static function definitionSet(): SetInterface
    {
        return self::$definitionSet ?? self::$definitionSet = Range::exclusive(
            new Integer(0),
            Infinite::positive()
        );
    }

    public function __toString(): string
    {
        return sprintf('lg(%s)', $this->number);
    }
}
