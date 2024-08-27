<?php

namespace Core\Helpers;

use Exception;

class Math
{
    /**
     * Evaluates a mathematical expression securely.
     *
     * @param string $expression The mathematical expression to evaluate.
     * @return float|int The result of the evaluated expression.
     * @throws Exception If the expression contains invalid characters.
     */
    public function evaluate(string $expression): float|int
    {
        // Allow only numbers, math operators, parentheses, and whitespace
        if (preg_match('/[^0-9+\-*\/\(\). ]/', $expression)) {
            throw new Exception('Invalid characters in expression.');
        }

        // Evaluate the expression securely
        return eval("return $expression;");
    }

    // Basic Mathematical Functions

    /**
     * Calculate the factorial of a number.
     * @throws Exception
     */
    public function factorial(int $number): float|int
    {
        if ($number < 0) {
            throw new Exception('Factorial is not defined for negative numbers.');
        }

        return ($number == 0) ? 1 : $number * $this->factorial($number - 1);
    }

    /**
     * Calculate the power of a base number.
     */
    public function power(mixed $base,mixed $exponent): float|object|int
    {
        return pow($base, $exponent);
    }

    /**
     * Calculate the square root of a number.
     * @throws Exception
     */
    public function squareRoot(float $number): float
    {
        if ($number < 0) {
            throw new Exception('Square root is not defined for negative numbers.');
        }

        return sqrt($number);
    }

    /**
     * Calculate the greatest common divisor (GCD) of two numbers.
     */
    public function gcd(float $a, float $b)
    {
        return ($b == 0) ? abs($a) : $this->gcd($b, $a % $b);
    }

    /**
     * Calculate the least common multiple (LCM) of two numbers.
     */
    public function lcm(float $a, float $b): float|int
    {
        return abs($a * $b) / $this->gcd($a, $b);
    }

    /**
     * Calculate the logarithm of a number.
     * @throws Exception
     */
    public function logarithm(float $number, $base = 10): float
    {
        if ($number <= 0 || $base <= 0) {
            throw new Exception('Logarithm is not defined for non-positive numbers.');
        }

        return log($number, $base);
    }

    // Trigonometric Functions

    public function sine(float $angle): float
    {
        return sin($angle);
    }

    public function cosine(float $angle): float
    {
        return cos($angle);
    }

    public function tangent(float $angle): float
    {
        return tan($angle);
    }

    // Additional Mathematical Functions

    /**
     * Calculate the absolute value of a number.
     */
    public function absolute(float $number): float|int
    {
        return abs($number);
    }

    /**
     * Calculate the exponential of a number (e^x).
     */
    public function exponential(float $number): float
    {
        return exp($number);
    }

    /**
     * Calculate the natural logarithm (ln) of a number.
     */
    public function naturalLog(float $number): float
    {
        return log($number);
    }

    /**
     * Round a number to the nearest integer.
     */
    public function roundNumber(float $number): float
    {
        return round($number);
    }

    /**
     * Round a number up to the nearest integer.
     */
    public function ceilNumber(float $number): float
    {
        return ceil($number);
    }

    /**
     * Round a number down to the nearest integer.
     */
    public function floorNumber(float $number): float
    {
        return floor($number);
    }

    // Statistical Functions

    /**
     * Calculate the mean (average) of an array of numbers.
     * @throws Exception
     */
    public function mean(array $numbers): float|int
    {
        if (count($numbers) == 0) {
            throw new Exception('Array must contain at least one number.');
        }

        return array_sum($numbers) / count($numbers);
    }

    /**
     * Calculate the median of an array of numbers.
     */
    public function median(array $numbers)
    {
        sort($numbers);
        $count = count($numbers);
        $middle = floor(($count - 1) / 2);

        if ($count % 2) {
            return $numbers[$middle];
        }

        return ($numbers[$middle] + $numbers[$middle + 1]) / 2.0;
    }

    /**
     * Calculate the variance of an array of numbers.
     * @throws Exception
     */
    public function variance(array $numbers): float|int
    {
        $mean = $this->mean($numbers);
        $sum = 0;

        foreach ($numbers as $number) {
            $sum += pow($number - $mean, 2);
        }

        return $sum / count($numbers);
    }

    /**
     * Calculate the standard deviation of an array of numbers.
     * @throws Exception
     */
    public function standardDeviation(array $numbers): float
    {
        return sqrt($this->variance($numbers));
    }

    // Geometric Functions

    /**
     * Calculate the area of a circle given its radius.
     * @throws Exception
     */
    public function circleArea(float $radius): float|int
    {
        if ($radius < 0) {
            throw new Exception('Radius cannot be negative.');
        }

        return pi() * pow($radius, 2);
    }

    /**
     * Calculate the circumference of a circle given its radius.
     * @throws Exception
     */
    public function circleCircumference(float $radius): float|int
    {
        if ($radius < 0) {
            throw new Exception('Radius cannot be negative.');
        }

        return 2 * pi() * $radius;
    }

    /**
     * Calculate the Pythagorean theorem (hypotenuse) given two sides of a right triangle.
     */
    public function pythagoreanTheorem(float $a,float $b): float
    {
        return sqrt(pow($a, 2) + pow($b, 2));
    }
}