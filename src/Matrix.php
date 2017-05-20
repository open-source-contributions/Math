<?php
declare(strict_types = 1);

namespace Innmind\Math;

use function Innmind\Math\numerize;
use Innmind\Math\{
    Matrix\RowVector,
    Matrix\ColumnVector,
    Exception\VectorsMustMeOfTheSameDimensionException,
    Exception\MatrixMustBeSquareException,
    Exception\MatricesMustBeOfTheSameDimensionException,
    Matrix\Dimension,
    Algebra\NumberInterface,
    Algebra\Number,
    Algebra\Integer
};
use Innmind\Immutable\{
    Sequence,
    StreamInterface,
    Stream
};

final class Matrix implements \Iterator
{
    private $dimension;
    private $rows;
    private $columns;

    public function __construct(RowVector $first, RowVector ...$rows)
    {
        $this->rows = (new Sequence($first, ...$rows))->reduce(
            new Stream(RowVector::class),
            function(Stream $carry, RowVector $row): Stream {
                return $carry->add($row);
            }
        );

        $this
            ->rows
            ->drop(1)
            ->foreach(function(RowVector $row) use ($first): void {
                if (!$row->dimension()->equals($first->dimension())) {
                    throw new VectorsMustMeOfTheSameDimensionException;
                }
            });

        $this->columns = new Stream(ColumnVector::class);
        $this->dimension = new Dimension(
            new Integer($this->rows->size()),
            $this->rows->get(0)->dimension()
        );
        $this->buildColumns();
    }

    public static function fromArray(array $values): self
    {
        $rows = [];

        foreach ($values as $numbers) {
            $rows[] = new RowVector(...numerize(...$numbers));
        }

        return new self(...$rows);
    }

    public static function fromColumns(
        ColumnVector $first,
        ColumnVector ...$columns
    ): self {
        $self = self::fromArray(
            (new Sequence($first, ...$columns))
                ->map(function(ColumnVector $column): array {
                    return iterator_to_array($column);
                })
                ->toPrimitive()
        );

        return $self->transpose();
    }

    /**
     * Initialize a matrix to the wished dimension filled with the specified value
     */
    public static function initialize(Dimension $dimension, NumberInterface $value): self
    {
        $rows = [];

        for ($i = 0; $i < $dimension->rows()->value(); ++$i) {
            $rows[] = new RowVector(
                ...array_fill(
                    0,
                    $dimension->columns()->value(),
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
            ->reduce(
                [],
                function(array $carry, RowVector $row) {
                    $carry[] = $row->toArray();

                    return $carry;
                }
            );
    }

    public function row(int $row): RowVector
    {
        return $this->rows->get($row);
    }

    public function column(int $column): ColumnVector
    {
        return $this->columns->get($column);
    }

    /**
     * @return StreamInterface<RowVector>
     */
    public function rows(): StreamInterface
    {
        return $this->rows;
    }

    /**
     * @return StreamInterface<ColumnVector>
     */
    public function columns(): StreamInterface
    {
        return $this->columns;
    }

    public function dropRow(int $row): self
    {
        return new self(
            ...$this
                ->rows
                ->slice(0, $row)
                ->append(
                    $this->rows->slice($row + 1, $this->rows->size())
                )
        );
    }

    public function dropColumn(int $column): self
    {
        return self::fromColumns(
            ...$this
                ->columns
                ->slice(0, $column)
                ->append(
                    $this->columns->slice($column + 1, $this->columns->size())
                )
        );
    }

    public function add(self $matrix): self
    {
        if (!$this->dimension->equals($matrix->dimension())) {
            throw new MatricesMustBeOfTheSameDimensionException;
        }

        $matrix->rewind();
        $rows = $this->rows->map(function(RowVector $row) use ($matrix) {
            $row = $row->add($matrix->current());
            $matrix->next();

            return $row;
        });

        return new self(...$rows);
    }

    public function subtract(self $matrix): self
    {
        if (!$this->dimension->equals($matrix->dimension())) {
            throw new MatricesMustBeOfTheSameDimensionException;
        }

        $matrix->rewind();
        $rows = $this->rows->map(function(RowVector $row) use ($matrix) {
            $row = $row->subtract($matrix->current());
            $matrix->next();

            return $row;
        });

        return new self(...$rows);
    }

    public function multiplyBy(NumberInterface $number): self
    {
        $rows = $this->rows->reduce(
            new Sequence,
            function(Sequence $rows, RowVector $row) use ($number): Sequence {
                return $rows->add(
                    $row->multiply(
                        RowVector::initialize($row->dimension(), $number)
                    )
                );
            }
        );

        return new self(...$rows);
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

    public function dot(self $matrix): self
    {
        $rows = $this->rows->reduce(
            new Sequence,
            function(Sequence $rows, RowVector $row) use ($matrix): Sequence {
                $newRow = $matrix
                    ->columns()
                    ->reduce(
                        new Sequence,
                        function(Sequence $carry, ColumnVector $column) use ($row): Sequence {
                            return $carry->add(
                                $row->dot($column)
                            );
                        }
                    );

                return $rows->add($newRow);
            }
        );

        return self::fromArray($rows->toPrimitive());
    }

    public function isSquare(): bool
    {
        return $this->dimension->rows()->equals($this->dimension->columns());
    }

    public function diagonal(): self
    {
        if (!$this->isSquare()) {
            throw new MatrixMustBeSquareException;
        }

        $rows = $this->rows->reduce(
            new Sequence,
            function(Sequence $rows, RowVector $row): Sequence {
                $numbers = $row->toArray();
                $newRow = array_fill(0, $row->dimension()->value(), 0);
                $index = $rows->size();
                $newRow[$index] = $numbers[$index];

                return $rows->add(new RowVector(...numerize(...$newRow)));
            }
        );

        return new self(...$rows);
    }

    public function identity(): self
    {
        if (!$this->isSquare()) {
            throw new MatrixMustBeSquareException;
        }

        $rows = $this->rows->reduce(
            new Sequence,
            function(Sequence $rows, RowVector $row): Sequence {
                $newRow = array_fill(0, $row->dimension()->value(), 0);
                $newRow[$rows->size()] = 1;

                return $rows->add(new RowVector(...numerize(...$newRow)));
            }
        );

        return new self(...$rows);
    }

    public function equals(self $matrix): bool
    {
        if (!$this->dimension->equals($matrix->dimension())) {
            return false;
        }

        $matrix->rewind();

        return $this->rows->reduce(
            true,
            function(bool $carry, RowVector $row) use ($matrix): bool {
                $carry = $carry && $row->equals($matrix->current());
                $matrix->next();

                return $carry;
            }
        );
    }

    public function isSymmetric(): bool
    {
        return $this->equals($this->transpose());
    }

    public function isAntisymmetric(): bool
    {
        return $this
            ->multiplyBy(new Integer(-1))
            ->equals($this->transpose());
    }

    public function isInRowEchelonForm(): bool
    {
        $zero = new Integer(0);
        $leadingZeros = $this->rows->reduce(
            new Sequence,
            function(Sequence $carry, RowVector $row) use ($zero): Sequence {
                $numbers = iterator_to_array($row);
                $dimension = $row->dimension()->value();
                $count = 0;

                for ($i = 1; $i < $dimension; $i++) {
                    if (!$numbers[$i]->equals($zero)) {
                        break;
                    }

                    ++$count;
                }

                return $carry->add($count);
            }
        );

        $previous = $leadingZeros->first();

        return $leadingZeros
            ->drop(1)
            ->reduce(
                true,
                function(bool $carry, int $count) use (&$previous): bool {
                    $carry = $carry && $count > $previous;
                    $previous = $count;

                    return $carry;
                }
            );
    }

    public function current(): RowVector
    {
        return $this->rows->current();
    }

    public function key(): int
    {
        return $this->rows->key();
    }

    public function next(): void
    {
        $this->rows->next();
    }

    public function rewind(): void
    {
        $this->rows->rewind();
    }

    public function valid(): bool
    {
        return $this->rows->valid();
    }

    private function buildColumns(): void
    {
        for ($i = 0; $i < $this->dimension->columns()->value(); ++$i) {
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
