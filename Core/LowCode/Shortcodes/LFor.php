<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LFor extends Shortcode implements LoopingStructure
{
    public static string|null $shortcode_name = "for";

    private int $loopStatus = 1;
    private bool $conditionMet = true; // Assume true initially for entering the loop

    public function __construct()
    {
        parent::__construct("for");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null, LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): bool|string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $attributes = $config['attributes'] ?? [];
        $start = isset($attributes['start']) ? (int) $attributes['start'] : 0;
        $end = isset($attributes['end']) ? (int) $attributes['end'] : 0;
        $step = isset($attributes['step']) ? (int) $attributes['step'] : 1;

        if (!isset($attributes['start']) || !isset($attributes['end'])) {
            throw new \Exception("Start and end values are required in [for] shortcode.");
        }

        // Replace variables in the start, end, and step
        $compiled_start = (int)$this->replaceVariables($start);
        $compiled_end = (int)$this->replaceVariables($end);
        $compiled_step = (int)$this->replaceVariables($step);

        // Execute the loop
        $output = "";
        for ($i = $compiled_start; $i <= $compiled_end; $i += $compiled_step) {
            $this->loopStatus = 1; // Reset loop status
            $output .= ShortcodeManager::parse($content, $this);

            if ($this->loopStatus == 0) {
                break; // Break loop
            } elseif ($this->loopStatus == 2) {
                continue; // Continue to next iteration
            }
        }

        return $output;
    }

    public function setLoopStatus(int $status = 1): void
    {
        $this->loopStatus = $status;
    }

    public function getLoopStatus(): int
    {
        return $this->loopStatus;
    }


    public function setConditionMet(bool $conditionMet): void
    {
        $this->$conditionMet = $conditionMet;
    }

    public function getConditionMet(): bool
    {
        return $this->conditionMet;
    }
}