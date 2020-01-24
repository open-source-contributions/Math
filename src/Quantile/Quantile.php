<?php
declare(strict_types = 1);

namespace Innmind\Math\Quantile;

use function Innmind\Math\{
    divide,
    add,
    mean,
    median,
    min,
    max
};
use Innmind\Math\{
    Regression\Dataset,
    Algebra\Number,
    Matrix\ColumnVector,
    Exception\OutOfRangeException
};

final class Quantile
{
    private $min;
    private $max;
    private $mean;
    private $median;
    private $firstQuartile;
    private $thirdQuartile;

    public function __construct(Dataset $dataset)
    {
        $values = $dataset->ordinates()->toArray();
        sort($values);
        $dataset = Dataset::fromArray($values);

        $this
            ->buildMin($dataset)
            ->buildMax($dataset)
            ->buildMean($dataset)
            ->buildMedian($dataset)
            ->buildFirstQuartile($dataset)
            ->buildThirdQuartile($dataset);
    }

    /**
     * Return the minimum value
     *
     * @return Quartile
     */
    public function min(): Quartile
    {
        return $this->quartile(0);
    }

    /**
     * Return the maxinum value
     *
     * @return Quartile
     */
    public function max(): Quartile
    {
        return $this->quartile(4);
    }

    /**
     * Return the mean value
     *
     * @return Number
     */
    public function mean(): Number
    {
        return $this->mean;
    }

    /**
     * Return the median value
     *
     * @return Quartile
     */
    public function median(): Quartile
    {
        return $this->quartile(2);
    }

    /**
     * Return the quartile at the wished index
     *
     * @param int $index
     *
     * @return Quartile
     */
    public function quartile(int $index): Quartile
    {
        switch ($index) {
            case 0:
                return $this->min;
            case 1:
                return $this->firstQuartile;
            case 2:
                return $this->median;
            case 3:
                return $this->thirdQuartile;
            case 4:
                return $this->max;
        }

        throw new OutOfRangeException((string) $index);
    }

    /**
     * Extract the minimum value from the dataset
     *
     * @param Dataset $dataset
     *
     * @return self
     */
    private function buildMin(Dataset $dataset): self
    {
        $this->min = new Quartile(min(...$dataset->ordinates()));

        return $this;
    }

    /**
     * Extract the maximum value from the dataset
     *
     * @param Dataset $dataset
     *
     * @return self
     */
    private function buildMax(Dataset $dataset): self
    {
        $this->max = new Quartile(max(...$dataset->ordinates()));

        return $this;
    }

    /**
     * Build the mean value from the dataset
     *
     * @param Dataset $dataset
     *
     * @return self
     */
    private function buildMean(Dataset $dataset): self
    {
        $this->mean = mean(...$dataset->ordinates());

        return $this;
    }

    /**
     * Extract the median from the dataset
     *
     * @param Dataset $dataset
     *
     * @return self
     */
    private function buildMedian(Dataset $dataset): self
    {
        $this->median = new Quartile(median(...$dataset->ordinates()));

        return $this;
    }

    /**
     * Extract the first quartile
     *
     * @param Dataset $dataset
     *
     * @return self
     */
    private function buildFirstQuartile(Dataset $dataset): self
    {
        $this->firstQuartile = new Quartile($this->buildQuartile(
            new Number\Number(0.25),
            $dataset->ordinates()
        ));

        return $this;
    }

    /**
     * Extract the first quartile
     *
     * @param Dataset $dataset
     *
     * @return self
     */
    private function buildThirdQuartile(Dataset $dataset): self
    {
        $this->thirdQuartile = new Quartile($this->buildQuartile(
            new Number\Number(0.75),
            $dataset->ordinates()
        ));

        return $this;
    }

    /**
     * Return the value describing the the quartile at the given percentage
     *
     * @param Number $percentage
     * @param ColumnVector $dataset
     *
     * @return float
     */
    private function buildQuartile(
        Number $percentage,
        ColumnVector $dataset
    ): Number {
        $dimension = $dataset->dimension();

        if ($dimension->value() === 2) {
            return divide(
                add($dataset->get(0), $dataset->get(1)),
                2
            );
        } else if ($dimension->value() === 1) {
            return $dataset->get(0);
        }

        $index = (int) $dimension
            ->multiplyBy($percentage)
            ->round()
            ->value();

        return divide(
            add($dataset->get($index), $dataset->get($index - 1)),
            2
        );
    }
}
