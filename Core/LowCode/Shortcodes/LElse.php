<?php

namespace Core\LowCode\Shortcodes;

use Core\LowCode\LowCodeModule;
use Core\LowCode\LowCodeTemplate;
use Core\LowCode\Shortcode;
use Core\LowCode\ShortcodeManager;

class LElse extends Shortcode
{
    public static string|null $shortcode_name = "else";
    private bool $conditionMet = false;
    public function __construct()
    {
        parent::__construct("else");
    }

    public function parse(string $content, array $config, LowCodeTemplate | LowCodeModule | Shortcode $parent = null,  Lif | LElse | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $attributes = $config['attributes'] ?? [];
        $condition = $attributes['main'] ?? null;

        if($this->elderSibling == null){
            return "";
        }

        if ($this->elderSibling instanceof Lif || $this->elderSibling instanceof LElse){
            if ($this->elderSibling->getConditionMet()){
                return  "";
            }

            if ($condition === null) {
                return ShortcodeManager::parse($content, $this);
            }

            // Replace variables in the condition
            $condition = $this->replaceVariables($condition);
            $this->conditionMet = $this->evaluateCondition("$condition");

            if ($this->conditionMet) {
                return ShortcodeManager::parse($content, $this);
            }
            return '';
        }
        else{
            throw new \Exception("Invalid Else shortcode placement");
        }
    }

    public function getConditionMet(): bool
    {
        if(($this->elderSibling instanceof Lif || $this->elderSibling instanceof LElse)){
            if($this->elderSibling->getConditionMet()){
                return true;
            }
        }
        return $this->conditionMet;
    }
}
