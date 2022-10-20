<?php

namespace App\ValueObjects;

use JetBrains\PhpStorm\Pure;

class Percentage
{
    /**
     * The current value.
     *
     * @var float
     */
    public readonly float $value;

    /**
     * The current value.
     *
     * @var float
     */
    public readonly float $raw;

    /**
     * The value when formatted.
     *
     * @var float|string
     */
    public readonly float|string $formatted;

    /**
     * Create a new Percentage instance.
     *
     * @param float $value
     */
    public function __construct(
        float $value
    ) {
        $this->value = $value;
        $this->raw = (int) round($this->value * 100);
        $this->formatted = $this->format();
    }

    public function __toString(): string
    {
        return $this->formatted;
    }

    /**
     * Format the value.
     *
     * @return string
     */
    protected function format()
    {
        return (int) round($this->value * 100) . '%';
    }

    /**
     * Turn the new values into a Percentage.
     *
     * @param float $numerator
     * @param float $denominator
     * @return Percentage
     */
    public static function from(float $numerator, float $denominator): Percentage
    {
        if ($denominator === 0.0) {
            return new self(0);
        }

        return new self($numerator / $denominator);
    }
}
