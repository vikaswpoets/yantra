<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LCase extends Shortcode
{
    public static string|null $shortcode_name = "case";

    public function __construct()
    {
        parent::__construct("case");
    }

    public function parse(string $content, array $config, Shortcode $parent = null, ?Shortcode $elderSibling = null): bool|string
    {
        if (!$parent instanceof LSwitch) {
            throw new \Exception("[case] shortcode must be inside a [switch] shortcode.");
        }

        // Get the case value from attributes
        $attributes = $config['attributes'] ?? [];
        $caseValue = $attributes['main'] ?? null;

        if ($caseValue === null) {
            throw new \Exception("Case value not provided.");
        }

        // Delegate the case processing to the LSwitch instance
        return $parent->processCase($caseValue, $content);
    }
}