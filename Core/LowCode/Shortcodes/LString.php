<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LString extends Shortcode
{
    public function __construct()
    {
        parent::__construct("string");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;
        $attr = $config['attributes'] ?? [];
        $action = $config['sections'][0] ?? '';
        $default = $attr['default'] ?? '';
        $output = '';

        switch ($action) {
            case 'concat':
                $strings = array_map([$this, 'replaceVariables'], $attr['strings'] ?? []);
                $output = implode('', $strings);
                break;

            case 'length':
                $string = $this->replaceVariables($attr['string'] ?? $default);
                $output = strlen($string);
                break;

            case 'upper':
                $string = $this->replaceVariables($attr['string'] ?? $default);
                $output = strtoupper($string);
                break;

            case 'lower':
                $string = $this->replaceVariables($attr['string'] ?? $default);
                $output = strtolower($string);
                break;

            case 'trim':
                $string = $this->replaceVariables($attr['string'] ?? $default);
                $output = trim($string);
                break;

            case 'substring':
                $string = $this->replaceVariables($attr['string'] ?? $default);
                $start = intval($attr['start'] ?? 0);
                $length = isset($attr['length']) ? intval($attr['length']) : null;
                $output = substr($string, $start, $length);
                break;

            case 'replace':
                $string = $this->replaceVariables($attr['string'] ?? $default);
                $search = $attr['search'] ?? '';
                $replace = $attr['replace'] ?? '';
                $output = str_replace($search, $replace, $string);
                break;

            default:
                $output = $default;
                break;
        }

        if (isset($attr['set'])) {
            $this->set($attr['set'], $output);
        }

        return $output;
    }
}