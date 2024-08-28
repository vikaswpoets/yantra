<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\Shortcode;
use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;

class LUnset extends Shortcode
{
    public static string|null $shortcode_name = "unset";

    public function __construct()
    {
        parent::__construct("unset");
    }

    public function parse(string $content, array $config, LowCodeTemplate|LowCodeModule|Shortcode $parent = null, ?Shortcode $elderSibling = null): bool|string
    {
        $this->parent = $parent;

        // Get the variable name to unset from attributes
        $attributes = $config['attributes'] ?? [];
        $variableName = $attributes['name'] ?? null;

        if ($variableName === null) {
            throw new \Exception("Variable name not provided in [unset] shortcode.");
        }

        // Replace any dynamic variables in the variable name
        $resolvedVariableName = $this->replaceVariables($variableName);

        // Unset the variable in the current context
        $this->unsetVariable($resolvedVariableName);

        return "";
    }

    private function unsetVariable(string $variableName): void
    {
        // Check if the variable exists in the context and unset it
        if ($this->parent !== null && $this->parent->hasVariable($variableName)) {
            $this->parent->removeVariable($variableName);
        } elseif (isset($this->$variableName)) {
            unset($this->$variableName);
        }
    }
}