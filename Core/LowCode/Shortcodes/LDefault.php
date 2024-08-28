<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LDefault extends Shortcode
{
    public static string|null $shortcode_name = "default";

    public function __construct()
    {
        parent::__construct("default");
    }

    public function parse(string $content, array $config, Shortcode $parent = null, ?Shortcode $elderSibling = null): bool|string
    {
        if (!$parent instanceof LSwitch) {
            throw new \Exception("[default] shortcode must be inside a [switch] shortcode.");
        }

        // Delegate the default processing to the LSwitch instance
        return $parent->processDefault($content);
    }
}