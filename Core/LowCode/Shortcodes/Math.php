<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\Helpers\Math as MathHelper;

class Math extends Shortcode
{
    public static string|null $shortcode_name = "math";

    public function __construct()
    {
        parent::__construct("math");
    }

    public function parse(string $content, array $config, LowCodeTemplate|LowCodeModule|Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $attr = $config['attributes'] ?? [];
        $action = $config['sections'][0] ?? '';
        $statement = $attr['main'] ?? '';

        $m = new MathHelper();
        //$result = "";

        // Switch case to handle different actions
        switch ($action) {
            case 'evaluate':
                $expression = $this->replaceVariables($statement);
                $result = $m->evaluate($expression);
                break;

            case 'absolute':
                $expression = $this->replaceVariables($statement);
                $result = $m->absolute((float)$expression);
                break;

            case 'factorial':
                $number = (int)$this->replaceVariables($statement);
                $result = $m->factorial($number);
                break;

            case 'power':
                $base = (float)($attr['base'] ?? 1);
                $exponent = (float)($attr['exponent'] ?? 1);
                $result = $m->power($base, $exponent);
                break;

            case 'sqrt':
                $number = (float)$this->replaceVariables($statement);
                $result = $m->squareRoot($number);
                break;

            case 'gcd':
                $a = (float)($attr['a'] ?? 0);
                $b = (float)($attr['b'] ?? 0);
                $result = $m->gcd($a, $b);
                break;

            case 'lcm':
                $a = (float)($attr['a'] ?? 0);
                $b = (float)($attr['b'] ?? 0);
                $result = $m->lcm($a, $b);
                break;

            case 'log':
                $number = (float)$this->replaceVariables($statement);
                $base = (float)($attr['base'] ?? 10);
                $result = $m->logarithm($number, $base);
                break;

            case 'exp':
                $number = (float)$this->replaceVariables($statement);
                $result = $m->exponential($number);
                break;

            case 'sin':
                $angle = (float)$this->replaceVariables($statement);
                $result = $m->sine($angle);
                break;

            case 'cos':
                $angle = (float)$this->replaceVariables($statement);
                $result = $m->cosine($angle);
                break;

            case 'tan':
                $angle = (float)$this->replaceVariables($statement);
                $result = $m->tangent($angle);
                break;

            case 'mean':
                $numbers = $this->replaceVariables($statement);
                $numbersArray = explode(',', $numbers); // Assuming numbers are comma-separated
                $result = $m->mean(array_map('floatval', $numbersArray));
                break;

            case 'median':
                $numbers = $this->replaceVariables($statement);
                $numbersArray = explode(',', $numbers);
                $result = $m->median(array_map('floatval', $numbersArray));
                break;

            case 'variance':
                $numbers = $this->replaceVariables($statement);
                $numbersArray = explode(',', $numbers);
                $result = $m->variance(array_map('floatval', $numbersArray));
                break;

            case 'stddev':
                $numbers = $this->replaceVariables($statement);
                $numbersArray = explode(',', $numbers);
                $result = $m->standardDeviation(array_map('floatval', $numbersArray));
                break;

            case 'circleArea':
                $radius = (float)$this->replaceVariables($statement);
                $result = $m->circleArea($radius);
                break;

            case 'circleCircumference':
                $radius = (float)$this->replaceVariables($statement);
                $result = $m->circleCircumference($radius);
                break;

            case 'pythagorean':
                $a = (float)($attr['a'] ?? 0);
                $b = (float)($attr['b'] ?? 0);
                $result = $m->pythagoreanTheorem($a, $b);
                break;

            default:
                throw new \Exception("Unsupported action: $action");
        }

        // If the `set` attribute is provided, store the result as a variable
        if (isset($attr['set'])) {
            $this->set($attr['set'], $result);
            return "";
        }

        return (string)$result;
    }
}
