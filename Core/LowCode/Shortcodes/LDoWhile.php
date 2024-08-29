<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LDoWhile extends Shortcode implements LoopingStructure
{
    public static string|null $shortcode_name = "dowhile";

    private bool $conditionMet = false;
    private int $loopStatus = 1;

    public function __construct()
    {
        parent::__construct("dowhile");
    }

    public function parse(string $content, array $config, Shortcode $parent = null, ?Shortcode $elderSibling = null): bool|string
    {
        $this->parent = $parent;

        // Get the condition from attributes
        $attributes = $config['attributes'] ?? [];
        $condition = $attributes['main'] ?? null;

        if ($condition === null) {
            throw new \Exception("Condition not provided in [dowhile] shortcode.");
        }

        // Initial condition replacement
        //$compiled_condition = $this->replaceVariables($condition);
        $this->conditionMet = $this->evaluateCondition($condition);

        // Output
        $output = "";

        do {
            $this->loopStatus = 1;
            // Execute the code block
            //var_dump($condition);
            $output .= ShortcodeManager::parse($content, $this);

            // Check loop status
            if ($this->loopStatus == 0) { // Break
                break;
            } elseif ($this->loopStatus == 2) { // Continue
                continue;
            }

            // Reevaluate the condition after each loop
            ///$compiled_condition = $this->replaceVariables($condition);
            $this->conditionMet = $this->evaluateCondition($condition);
        } while ($this->conditionMet);

        return $output;
    }

    public function setConditionMet(bool $conditionMet): void
    {
        $this->conditionMet = $conditionMet;
    }

    public function getConditionMet(): bool
    {
        return $this->conditionMet;
    }

    public function setLoopStatus(int $status = 1): void
    {
        $this->loopStatus = $status;
    }

    public function getLoopStatus(): int
    {
        return $this->loopStatus;
    }
}