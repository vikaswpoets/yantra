<?php

namespace Core\LowCode;

class LowCodeTemplate extends Shortcode
{
    public string $name;

    public array $attributes = [];
    public array $variables = [];

    private  string $content;

    public function __construct(string $name,string $content)
    {
        parent::__construct($name);
        $this->content = $content;
    }

    public function setParent(LowCodeModule|Shortcode|null $parent): void {
        $this->parent = $parent;
    }

    public function getParent(): LowCodeModule|Shortcode|null {
        return $this->parent;
    }

    /**
     * Set a variable for the module, supporting deep array access using dot notation.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        if($this->name == 'main' || str_starts_with($key,'module.')|| str_starts_with($key,'env.'))
        {
            $this->parent->set($key, $value);
        }
        else {
            if (str_starts_with($key, "template.")) {
                $key = substr($key, strlen("template."));
            }
            $keys = explode('.', $key);
            LowCode::setByPath($keys, $value,$this->variables);
        }
    }

    /**
     * Get a variable from the module, supporting deep array access using dot notation.
     *
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key): mixed
    {
        if($this->name == 'main' || str_starts_with(trim($key),'module.')|| str_starts_with(trim($key),'env.'))
        {
            return $this->parent->get($key);
        }
        else {
            if (str_starts_with($key, "template.")) {
                $key = substr($key, strlen("template."));
            }
            $keys = explode('.', $key);
            return LowCode::getByPath( $keys,$this->variables);
        }
    }
    public function setAttributes(array $attributes): void
    {
        $this->attributes = $attributes;
    }

    public function parse(string $content, array $config, ?Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        return parent::parse($this->content,[],$parent);
    }
}