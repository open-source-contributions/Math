<?php
declare(strict_types = 1);

namespace Innmind\Math\Algebra;

final class Modulo implements Operation, Number
{
    private $number;
    private $modulus;
    private $result;

    public function __construct(Number $number, Number $modulus)
    {
        $this->number = $number;
        $this->modulus = $modulus;
    }

    public function result(): Number
    {
        return $this->result ?? $this->result = Number\Number::wrap(
            fmod($this->number->value(), $this->modulus->value())
        );
    }

    /**
     * {@inheritdoc}
     */
    public function value()
    {
        return $this->result()->value();
    }

    public function equals(Number $number): bool
    {
        return $this->result()->equals($number);
    }

    public function higherThan(Number $number): bool
    {
        return $this->result()->higherThan($number);
    }

    public function add(Number $number, Number ...$numbers): Number
    {
        return new Addition($this, $number, ...$numbers);
    }

    public function subtract(Number $number, Number ...$numbers): Number
    {
        return new Subtraction($this, $number, ...$numbers);
    }

    public function divideBy(Number $number): Number
    {
        return new Division($this, $number);
    }

    public function multiplyBy(Number $number, Number ...$numbers): Number
    {
        return new Multiplication($this, $number, ...$numbers);
    }

    public function round(int $precision = 0, string $mode = Round::UP): Number
    {
        return new Round($this, $precision, $mode);
    }

    public function floor(): Number
    {
        return new Floor($this);
    }

    public function ceil(): Number
    {
        return new Ceil($this);
    }

    public function modulo(Number $modulus): Number
    {
        return new self($this, $modulus);
    }

    public function absolute(): Number
    {
        return new Absolute($this);
    }

    public function power(Number $power): Number
    {
        return new Power($this, $power);
    }

    public function squareRoot(): Number
    {
        return new SquareRoot($this);
    }

    public function exponential(): Number
    {
        return new Exponential($this);
    }

    public function binaryLogarithm(): Number
    {
        return new BinaryLogarithm($this);
    }

    public function naturalLogarithm(): Number
    {
        return new NaturalLogarithm($this);
    }

    public function commonLogarithm(): Number
    {
        return new CommonLogarithm($this);
    }

    public function signum(): Number
    {
        return new Signum($this);
    }

    public function __toString(): string
    {
        $number = $this->number instanceof Operation ?
            '('.$this->number.')' : $this->number;
        $modulus = $this->modulus instanceof Operation ?
            '('.$this->modulus.')' : $this->modulus;

        return $number.' % '.$modulus;
    }
}
