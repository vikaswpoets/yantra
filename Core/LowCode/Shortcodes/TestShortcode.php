<?php

// Core/sh/TestShortcode.php
namespace Core\LowCode\Shortcodes;

use Core\LowCode\Shortcode;

class TestShortcode extends Shortcode
{
    public function render(): string
    {
        return sprintf("<div>Test Shortcode: %s</div>", $this->getContent());
    }
}