<?php

namespace Core;

use Exception;

class UIComponent
{
    protected string $componentName;
    protected string $viewPath;
    protected array $attributes;
    protected array $childComponents = [];

    public function __construct(string $componentName, array $attributes = [], string $viewPath = '')
    {
        $this->componentName = $componentName;
        $this->attributes = $attributes;
        //$this->viewPath = !empty($viewPath) ? "{$viewPath}/components/{$this->componentName}.php" : $this->getDefaultComponentPath();
        $this->viewPath = !empty($viewPath)?$viewPath:apply_filter('view_path',(__DIR__ ."/../Views/"));

    }

    public function addChildComponent(string $slot, UIComponent $component): void
    {
        $this->childComponents[$slot] = $component;
    }

    public function addAttribute(string $key, $value): void
    {
        $this->attributes[$key] = $value;
    }

    /**
     * @throws Exception
     */
    public function render(): bool|string
    {
        $content_path = "{$this->viewPath}components/{$this->componentName}.php";
        if (!file_exists($content_path)) {
            throw new Exception("Component template not found: {$content_path}");
        }

        // Include child components in the attributes array before extracting variables
        foreach ($this->childComponents as $slot => $component) {
            $this->attributes[$slot] = $component->render();
        }

        // Extract attributes as variables for use in the template
        extract($this->attributes);

        // Start output buffering
        ob_start();

        // Include the component template
        include $content_path;

        // Get the buffered content
        return ob_get_clean();
    }

    public function renderScript(array $data = []): void
    {
        if (file_exists("{$this->viewPath}components/{$this->componentName}_script.js")) {
            echo file_get_contents("{$this->viewPath}components/{$this->componentName}_script.js");
        }

        if (file_exists("{$this->viewPath}components/{$this->componentName}_script.php")) {
            extract($this->attributes);
            extract($data);
            include "{$this->viewPath}components/{$this->componentName}_script.php";
        }
    }

    public function renderStyles(array $data = []): void
    {
        if (file_exists("{$this->viewPath}components/{$this->componentName}_style.css")) {
            echo file_get_contents("{$this->viewPath}components/{$this->componentName}_style.css");
        }
    }

    public function init( ): void
    {
        $file = "{$this->viewPath}components/{$this->componentName}_style.css";
        if (file_exists($file)) {
            add_filter('footer_script', callback: function () use ($url) {
                echo sprintf("<script type='text/javascript' src='%s'></script>", $url);
            });
        }
        if ($type == 'header_scripts') {
            add_action('page_header', callback: function () use ($url) {
                echo sprintf("<script type='text/javascript' src='%s'></script>", $url);
            });
        }
        if ($type == 'style') {
            add_action('page_header', callback: function () use ($url) {
                echo sprintf("<script type='text/javascript' src='%s'></script>", $url);
            }, priority: 8);
        }
    }
}