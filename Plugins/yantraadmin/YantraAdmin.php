<?php
namespace Plugins\yantraadmin;
use System\Plugin;

class yantraadmin extends Plugin {
    private string $plugin_name = 'Yanta Admin a default CMS';
    private string $plugin_description = 'This is an example plugin for demonstration purposes.';
    private string $plugin_version = '1.0.0';
    private string $plugin_author = 'araaiv';

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