<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LSwitch extends Shortcode
{
    public static string|null $shortcode_name = "switch";

    private string $switchValue;
    private bool $caseMatched = false;

    public function __construct()
    {
        parent::__construct("switch");
    }

    public function parse(string $content, array $config, Shortcode $parent = null, ?Shortcode $elderSibling = null): bool|string
    {
        $this->parent = $parent;

        // Get the switch value from attributes
        $attributes = $config['attributes'] ?? [];
        $this->switchValue = $attributes['main'] ?? null;

        if ($this->switchValue === null) {
            throw new \Exception("Switch value not provided.");
        }

        // Replace variables in the switch value
        $this->switchValue = $this->replaceVariables($this->switchValue);

        // Parse the content
        return ShortcodeManager::parse($content, $this);
    }

    public function processCase(string $caseValue, string $content): bool|string
    {
        // Replace variables in the case value
        $compiledCaseValue = $this->replaceVariables($caseValue);

        // Check if the case matches the switch value
        if (!$this->caseMatched && $compiledCaseValue == $this->switchValue) {
            $this->caseMatched = true;
            return ShortcodeManager::parse($content, $this);
        }

        return "";
    }

    /**
     * @throws \Exception
     */
    public function processDefault(string $content): bool|string
    {
        // If no case was matched, return the default content
        if (!$this->caseMatched) {
            return ShortcodeManager::parse($content, $this);
        }

        return "";
    }

    public function reset(): void
    {
        $this->caseMatched = false;
    }
}