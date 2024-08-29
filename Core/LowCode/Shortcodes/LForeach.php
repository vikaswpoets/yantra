<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LForeach extends Shortcode
{
    public static string|null $shortcode_name = "foreach";

    private int $loopStatus = 1;
    private array $variables = [];

    public function __construct()
    {
        parent::__construct("foreach");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null, LowCodeTemplate | LowCodeModule | Shortcode $elderSibling = null): bool|string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $attributes = $config['attributes'] ?? [];
        $array = $attributes['array'] ?? null;
        $keyVar = $attributes['key'] ?? 'key';
        $valueVar = $attributes['main'] ?? 'value';

        if ($array === null) {
            throw new \Exception("Array not provided in [foreach] shortcode.");
        }

        // Replace variables in the array attribute
        $compiled_array = $this->replaceVariables($array);

        // Decode the array from JSON format (assuming array is passed as JSON string)
        $decoded_array = json_decode($compiled_array, true);

        if (!is_array($decoded_array)) {
            throw new \Exception("Invalid array format provided in [foreach] shortcode.");
        }

        // Execute the loop
        $output = "";
        foreach ($decoded_array as $key => $value) {
            $this->loopStatus = 1; // Reset loop status

            // Assign key and value as variables available in the content
            $this->addVariable($keyVar, $key);
            $this->addVariable($valueVar, $value);

            // Parse the content for the current iteration
            $output .= ShortcodeManager::parse($content, $this);

            if ($this->loopStatus == 0) {
                break; // Break loop
            } elseif ($this->loopStatus == 2) {
                continue; // Continue to next iteration
            }
        }

        return $output;
    }

    private function addVariable(mixed $varName, mixed $value): void
    {
        // Store the variable in the variables array
        $this->variables[$varName] = $value;
    }

    public function getVariable(string $varName): mixed
    {
        return $this->variables[$varName] ?? null;
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
