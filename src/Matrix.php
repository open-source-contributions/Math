<?php
declare(strict_types = 1);

namespace Innmind\Math;

use Innmind\Math\{
    Vector\RowVector,
    Vector\ColumnVector,
    Exception\MatrixCannotBeEmptyException,
    Exception\VectorsMustMeOfTheSameDimensionException,
    Matrix\Dimension
};
use Innmind\Immutable\Sequence;

final class Matrix implements \Iterator
{
    private $dimension;
    private $rows;
    private $columns;

    public function __construct(RowVector ...$rows)
    {
        if (($count = count($rows)) < 1) {
            throw new MatrixCannotBeEmptyException;
        }

        for ($i = 1; $i < $count; ++$i) {
            if ($rows[$i]->dimension() !== $rows[$i - 1]->dimension()) {
                throw new VectorsMustMeOfTheSameDimensionException;
            }
        }

        $this->rows = new Sequence(...$rows);
        $this->columns = new Sequence;
        $this->dimension = new Dimension(
            $count,
            $this->rows->get(0)->dimension()
        );
        $this->buildColumns();
    }

    public static function fromArray(array $values): self
    {
        $rows = [];

        foreach ($values as $numbers) {
            $rows[] = new RowVector(...$numbers);
        }

        return new self(...$rows);
    }

    /**
     * Initialize a matrix to the wisjed dimension filled with the specified value
     */
    public static function initialize(Dimension $dimension, float $value): self
    {
        $rows = [];

        for ($i = 0; $i < $dimension->rows(); ++$i) {
            $rows[] = new RowVector(
                ...array_fill(
                    0,
                    $dimension->columns(),
                    $value
                )
            );
        }

        return new self(...$rows);
    }

    public function dimension(): Dimension
    {
        return $this->dimension;
    }

    public function toArray(): array
    {
        return $this
            ->rows
            ->map(function(RowVector $row) {
                return $row->toArray();
            })
            ->toPrimitive();
    }

    public function row(int $row): RowVector
    {
        return $this->rows->get($row);
    }

    public function column(int $column): ColumnVector
    {
        return $this->columns->get($column);
    }

    public function transpose(): self
    {
        $rows = $this->columns->reduce(
            [],
            function(array $rows, ColumnVector $column): array {
                $rows[] = new RowVector(...$column);

                return $rows;
            }
        );

        return new self(...$rows);
    }

    public function multiply(self $matrix): self
    {
        $expectedM = $this->dimension->rows();
        $expectedN = $matrix->dimension()->columns();
        $rows = [];

        for ($i = 0; $i < $expectedM; ++$i) {
            $row = [];

            for ($j = 0; $j < $expectedN; ++$j) {
                $row[] = $this->row($i)->dot($matrix->column($j));
            }

            $rows[] = $row;
        }

        return self::fromArray($rows);
    }

    public function current()
    {
        return $this->rows->current();
    }

    public function key()
    {
        return $this->rows->key();
    }

    public function next()
    {
        $this->rows->next();
    }

    public function rewind()
    {
        $this->rows->rewind();
    }

    public function valid()
    {
        return $this->rows->valid();
    }

    private function buildColumns()
    {
        for ($i = 0; $i < $this->dimension->columns(); ++$i) {
            $values = $this->rows->reduce(
                [],
                function(array $values, RowVector $row) use ($i) {
                    $values[] = $row->get($i);

                    return $values;
                }
            );
            $this->columns = $this->columns->add(new ColumnVector(...$values));
        }
    }
}