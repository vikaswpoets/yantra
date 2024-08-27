<?php

namespace Plugins\SamplePlugin;

use System\Plugin;

class SamplePlugin extends Plugin{
    protected $plugin_name = 'Example Plugin';
    protected $plugin_description = 'This is an example plugin for demonstration purposes.';
    protected $plugin_version = '1.0.0';
    protected $plugin_author = 'araaiv';

    public function activate(): bool
    {
        return true;
    }

    public function deactivate(): bool
    {
        return false;
    }


    public function path(): string
    {
        return __DIR__;
    }
}
