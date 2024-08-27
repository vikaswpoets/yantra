<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class Lif extends Shortcode
{
    public static string|null $shortcode_name = "if";
    private bool $conditionMet = false;

    public function __construct()
    {
        parent::__construct("if");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null, LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        //$this->setRawContent($content); // Set the raw content here
        $attributes = $config['attributes'] ?? [];
        $condition = $attributes['main'] ?? null;

        if ($condition === null) {
            throw new \Exception("Condition not provided in [if] shortcode.");
        }

        // Replace variables in the condition
        $condition = $this->replaceVariables($condition);

        // Evaluate the condition
        $this->conditionMet = $this->evaluateCondition("$condition");


        if ($this->conditionMet) {
            return ShortcodeManager::parse($content, $this);
        }

        return ''; // Return empty string if no condition is met
    }



    public function getConditionMet(): bool
    {
        return $this->conditionMet;
    }
}
