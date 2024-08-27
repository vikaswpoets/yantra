<?php

namespace Core\LowCode;

use Exception;

class LowCodeModule extends Shortcode
{
    public string $name;
    private array $templates = [];
   /// private string $content = '';
    public array $variables= [];

    private string $content;

    /**
     * @throws Exception
     */
    public function __construct(string $name, string $content="")
    {
        parent::__construct($name);
        if(!empty($content))
            $this->content=$content;
    }


    public function parse(string $content, array $config, ?Shortcode $parent = null,  LowCodeTemplate | LowCodeModule | Shortcode  $elderSibling = null): string
    {
        $this->parent = $parent;
        $this->elderSibling = $elderSibling;

        $sections = $config['sections']??[];
        $module = $sections[0]??'';
        $template = $sections[1]??'main';
        $attributes = $config['attributes']??[];
        if(empty(trim($module))){
            return $content;
        }
        return $this->run($template,$attributes);
    }

    /**
     * Run the specified template with the given attributes.
     *
     * @param string $template
     * @param array $attributes
     * @return string
     * @throws Exception
     */
    public function run(string $template, array $attributes = []): string
    {
        //LowCode::print_r($attributes);
        // Get the template object
        $templateObj = $this->getTemplate($template);
        //var_dump($templateObj);
        // Ensure the template exists and render it
        if ($templateObj) {
            return $templateObj->parse("",array('attributes'=>$attributes),$this);
        }

        // Throw exception if template not found
        throw new TemplateNotFoundException("Template not found: $template");
    }


    /**
     * Set a variable for the module, supporting deep array access using dot notation.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, mixed $value): void
    {
        if(str_starts_with($key,'env.'))
        {
            LowCode::set($key, $value);
        }
        else {
            if (str_starts_with($key, "module.")) {
                $key = substr($key, strlen("module."));
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
        if(str_starts_with($key,'env.'))
        {
            return LowCode::get($key);
        }
        else {
            if (str_starts_with($key, "module.")) {
                $key = substr($key, strlen("module."));
            }
            $keys = explode('.', $key);
            return LowCode::getByPath($keys,$this->variables);
        }
    }

    /**
     * Parse the templates from the module content.
     *
     * @param string|null $templateName
     */
    private function parseTemplates(string $templateName = null): void
    {
        // Regex pattern to extract templates from the module content
//        $pattern = '/\[:template\s+([^\s\]]+)](.*?)\[\/:template]/m';
        $pattern = ShortcodeManager::getPattern();
        // Match all templates in the content
        preg_match_all($pattern, $this->content, $matches, PREG_SET_ORDER);
        //LowCode::print_r($matches);
        foreach ($matches as $match) {
            $name = trim($match[2],'.');
            $templateContent = $match[5]??'';
            // Add the template only if the name matches, or it's null (i.e., load all templates)
            if ($templateName == null || trim($name) === trim($templateName)) {
                $this->templates[trim($name)] = new LowCodeTemplate(trim($name), $templateContent);
            }
        }
    }

    /**
     * Get a specific template by name, with lazy loading.
     *
     * @param string $name
     * @return LowCodeTemplate|null
     */
    private function getTemplate(string $name): ?LowCodeTemplate
    {
        // Lazy load template if not already loaded
        if (!isset($this->templates[$name]))
        {
           $this->parseTemplates($name);
        }
        return $this->templates[$name] ?? null;
    }

}