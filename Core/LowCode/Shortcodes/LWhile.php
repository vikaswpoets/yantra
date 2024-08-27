<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LWhile extends Shortcode implements LoopingStructure
{
    public static string|null $shortcode_name = "while";
    private bool $conditionMet = false;
    public function __construct()
    {
        parent::__construct("while");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null, LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): bool|string
    {
        $this->parent = $parent;
        $this->elderSibling = null;

        //$this->setRawContent($content); // Set the raw content here
        $attributes = $config['attributes'] ?? [];
        $condition = $attributes['main'] ?? null;

        if ($condition === null) {
            throw new \Exception("Condition not provided in [if] shortcode.");
        }
        // Replace variables in the condition
        $compiled_condition = $this->replaceVariables($condition);

        // Evaluate the condition
        $this->conditionMet = $this->evaluateCondition($compiled_condition);
        $output = "";
        while ($this->conditionMet){
            $this->loopStatus=1;
            $output .= ShortcodeManager::parse($content, $this);
            if($this->loopStatus == 0)
                break;
            elseif($this->loopStatus == 2)
                continue;
            // Replace variables in the condition
            $compiled_condition = $this->replaceVariables($condition);
            // Evaluate the condition
            $this->conditionMet = $this->evaluateCondition($compiled_condition);
        }
        return $output;
    }

    public function setConditionMet(bool $conditionMet): void
    {
        $this->$conditionMet = $conditionMet;
    }

    public function getConditionMet(): bool
    {
        return $this->conditionMet;
    }

    private int $loopStatus = 1;
    public function setLoopStatus(int $status=1): void
    {
        $this->loopStatus = $status;
    }

    public function getLoopStatus(): int
    {
        return $this->loopStatus;
    }
}
