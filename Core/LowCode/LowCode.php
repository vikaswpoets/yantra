<?php
namespace Core\LowCode;

use Exception;

class LowCode
{
    protected static array $env = [];
    public static array $shortcodes = [];
    public static array $modules = [];
    protected static array $reserved = ['shortcodes', 'Modules', 'templates', 'main', 'headers'];

    /**
     * @throws Exception
     */
    public static function init():void
    {
        self::initLanguageShortcodes();
        self::initLanguageModules();
        ShortcodeManager::register('module','Core\LowCode\LowCodeModule');
        ShortcodeManager::register('template','Core\LowCode\LowCodeTemplate');
    }

    /**
     * Initialize and register all language shortcodes.
     *
     * @throws Exception
     */
    private static function initLanguageShortcodes(): void
    {
        // Define the namespace and directory path for shortcodes
        $namespace = 'Core\\LowCode\\Shortcodes\\';
        $shortcodesDirectory = __DIR__ . '/Shortcodes/'; // Adjust this path accordingly

        // Ensure the directory exists
        if (!is_dir($shortcodesDirectory)) {
            throw new Exception("Shortcodes directory not found.");
        }

        // Scan the directory for all PHP files
        $shortcodeFiles = glob($shortcodesDirectory . '*.php');

        // Register each shortcode class
        foreach ($shortcodeFiles as $file) {
            // Extract the class name from the file
            $shortcodeClassWithNamespace = $namespace . pathinfo($file, PATHINFO_FILENAME);

            // Autoload the class and check if it exists
            if (class_exists($shortcodeClassWithNamespace)) {
                // Convert the class name to lowercase
                $lowerCaseClassName = strtolower(pathinfo($file, PATHINFO_FILENAME));

                $shname = $shortcodeClassWithNamespace::$shortcode_name;

                // Register using the lowercase class name and the full class name with namespace
                ShortcodeManager::register($shname??$lowerCaseClassName, $shortcodeClassWithNamespace);
            } /*else {
                throw new Exception("Shortcode class not found: $shortcodeClassWithNamespace");
            }*/
        }
    }

    /**
     * Initialize and register all language modules.
     *
     * @throws ModuleNotFoundException
     * @throws Exception
     */
    private static function initLanguageModules(): void
    {
        // Define the directory path for modules
        $modulesDirectory = __DIR__ . '/Modules/'; // Adjust this path accordingly

        // Ensure the directory exists
        if (!is_dir($modulesDirectory)) {
            throw new Exception("Modules directory not found.");
        }

        // Scan the directory for all .ym module files
        $moduleFiles = glob($modulesDirectory . '*.ym');

        // Register each module by its lowercase filename
        foreach ($moduleFiles as $file) {
            $moduleName = strtolower(pathinfo($file, PATHINFO_FILENAME));

            // Register the module name with the ModuleManager
            ModuleManager::register($moduleName, $modulesDirectory);
        }
    }

    /**
     * Set a variable for the module, supporting deep array access using dot notation.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set(string $key, mixed $value): void
    {
        if (str_starts_with($key, "env.")) {
            $key = substr($key, strlen("env."));
        }
        $keys = explode('.', $key);
        LowCode::setByPath( $keys, $value,LowCode::$env);
    }

    /**
     * Get a variable from the module, supporting deep array access using dot notation.
     *
     * @param string $key
     * @return mixed|null
     */
    public static  function get(string $key): mixed
    {
        if (str_starts_with($key, "env.")) {
            $key = substr($key, strlen("env."));
        }
        $keys = explode('.', $key);
        return LowCode::getByPath($keys,LowCode::$env);
    }

    /**
     * Helper function to set a value in a nested array using a path (keys array).
     *
     * @param array &$array
     * @param array $keys
     * @param mixed $value
     */
    public static function setByPath(array $keys, mixed $value, array &$array = null): void
    {
        if($array ===null)
            $current = &self::$env;
        else
            $current = &$array;

        foreach ($keys as $key) {
            if (!isset($current[$key]) || !is_array($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }
        $current = $value;
    }

    /**
     * Helper function to get a value from a nested array using a path (keys array).
     *
     * @param array $array
     * @param array $keys
     * @return mixed|null
     */
    public static function getByPath(array $keys, array $array = null): mixed
    {
        if($array === null)
            $current = self::$env;
        else
            $current = $array;
        foreach ($keys as $key) {
            $current = $current[$key] ?? null;
        }
        return $current;
    }

}
