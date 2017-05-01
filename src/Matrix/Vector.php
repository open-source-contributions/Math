<?php
declare(strict_types = 1);

namespace Innmind\Math\Matrix;

use function Innmind\Math\add;
use Innmind\Math\{
    Exception\VectorsMustMeOfTheSameDimensionException,
    Exception\VectorCannotBeEmptyException,
    Matrix,
    Algebra\NumberInterface,
    Algebra\Number
};
use Innmind\Immutable\Sequence;

final class Vector implements \Iterator
{
    private $numbers;

    public function __construct(NumberInterface ...$numbers)
    {
        $this->numbers = new Sequence(...$numbers);
        $this->dimension = new Number($this->numbers->size());

        if ($this->dimension()->value() === 0) {
            throw new VectorCannotBeEmptyException;
        }
    }

    public static function initialize(int $dimension, NumberInterface $value): self
    {
        return new self(...array_fill(0, $dimension, $value));
    }

    /**
     * @return int|float[]
     */
    public function toArray(): array
    {
        return $this
            ->numbers
            ->map(function(NumberInterface $number) {
                return $number->value();
            })
            ->toPrimitive();
    }

    public function dimension(): NumberInterface
    {
        return $this->dimension;
    }

    /**
     * @see https://en.wikipedia.org/wiki/Row_and_column_vectors#Operations
     */
    public function dot(self $vector): NumberInterface
    {
        if (!$this->dimension()->equals($vector->dimension())) {
            throw new VectorsMustMeOfTheSameDimensionException;
        }

        $vector->rewind();

        return $this->numbers->reduce(
            new Number(0),
            function(NumberInterface $carry, NumberInterface $number) use ($vector): NumberInterface {
                $value = $carry->add(
                    $number->multiplyBy($vector->current())
                );
                $vector->next();

                return $value;
            }
        );
    }

    public function multiply(self $vector): self
    {
        if (!$this->dimension()->equals($vector->dimension())) {
            throw new VectorsMustMeOfTheSameDimensionException;
        }

        $vector->rewind();
        $numbers = $this->numbers->map(function(NumberInterface $number) use ($vector): NumberInterface {
            $number = $number->multiplyBy($vector->current());
            $vector->next();

            return $number;
        });

        return new self(...$numbers);
    }

    public function divide(self $vector): self
    {
        if (!$this->dimension()->equals($vector->dimension())) {
            throw new VectorsMustMeOfTheSameDimensionException;
        }

        $vector->rewind();
        $numbers = $this->numbers->map(function(NumberInterface $number) use ($vector): NumberInterface {
            $number = $number->divideBy($vector->current());
            $vector->next();

            return $number;
        });

        return new self(...$numbers);
    }

    public function subtract(self $vector): self
    {
        if (!$this->dimension()->equals($vector->dimension())) {
            throw new VectorsMustMeOfTheSameDimensionException;
        }

        $vector->rewind();
        $numbers = $this->numbers->reduce(
            [],
            function(array $numbers, NumberInterface $number) use ($vector): array {
                $numbers[] = $number->subtract($vector->current());
                $vector->next();

                return $numbers;
            }
        );

        return new self(...$numbers);
    }

    public function add(self $vector): self
    {
        if (!$this->dimension()->equals($vector->dimension())) {
            throw new VectorsMustMeOfTheSameDimensionException;
        }

        $vector->rewind();
        $numbers = $this->numbers->reduce(
            [],
            function(array $numbers, NumberInterface $number) use ($vector): array {
                $numbers[] = $number->add($vector->current());
                $vector->next();

                return $numbers;
            }
        );

        return new self(...$numbers);
    }

    public function power(NumberInterface $power): self
    {
        $numbers = $this->numbers->map(function(NumberInterface $number) use ($power): NumberInterface {
            return $number->power($power);
        });

        return new self(...$numbers);
    }

    public function sum(): NumberInterface
    {
        return add(...$this->numbers);
    }

    public function get(int $position): NumberInterface
    {
        return $this->numbers->get($position);
    }

    public function current()
    {
        return $this->numbers->current();
    }

    public function key()
    {
        return $this->numbers->key();
    }

    public function next()
    {
        $this->numbers->next();
    }

    public function rewind()
    {
        $this->numbers->rewind();
    }

    public function valid()
    {
        return $this->numbers->valid();
    }
}