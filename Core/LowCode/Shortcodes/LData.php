<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LData extends Shortcode
{
    public function __construct()
    {
        parent::__construct("data");
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
            case 'jsondecode':
                $jsonData = $this->replaceVariables($attr['data'] ?? $default);
                $output = json_decode($jsonData, true);
                break;

            case 'arraypush':
                $array = $this->get($attr['array']);
                $value = $this->replaceVariables($attr['main'] ?? $default);
                $array[] = $value;
                $output = $array;
                break;

            case 'arrayslice':
                $array = $this->get($attr['array']);
                $offset = intval($attr['offset'] ?? 0);
                $length = isset($attr['length']) ? intval($attr['length']) : null;
                $output = array_slice($array, $offset, $length);
                break;

            case 'arraymerge':
                $array1 = $this->get($attr['array1']);
                $array2 = $this->get($attr['array2']);
                $output = array_merge($array1, $array2);
                break;

            case 'arraysearch':
                $array = $this->get($attr['array']);
                $value = $this->replaceVariables($attr['main'] ?? $default);
                $output = array_search($value, $array);
                break;

            default:
                $output = $default;
                break;
        }

        if (isset($attr['set'])) {
            $this->set($attr['set'], $output);
        }

        return is_array($output) ? json_encode($output) : (string)$output;
    }
}