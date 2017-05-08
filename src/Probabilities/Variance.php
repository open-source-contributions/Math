<?php
declare(strict_types = 1);

namespace Innmind\Math\Probabilities;

use Innmind\Math\{
    Regression\Dataset,
    Matrix\ColumnVector,
    Algebra\NumberInterface,
    Algebra\Integer
};

final class Variance
{
    private $variance;

    public function __construct(Dataset $dataset)
    {
        $expectation = (new Expectation($dataset))();
        $this->variance = $dataset
            ->abscissas()
            ->subtract(
                ColumnVector::initialize(
                    $dataset->abscissas()->dimension(),
                    $expectation
                )
            )
            ->power(new Integer(2))
            ->multiply($dataset->ordinates())
            ->sum();
    }

    public function __invoke(): NumberInterface
    {
        return $this->variance;
    }
}