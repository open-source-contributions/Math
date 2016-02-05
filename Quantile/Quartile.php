<?php
declare(strict_types = 1);

namespace Innmind\Math\Quantile;

/**
 * Holds a quartile value
 */
final class Quartile
{
    private $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    /**
     * Return the quartile value
     *
     * @return float
     */
    public function value(): float
    {
        return $this->value;
    }
}
