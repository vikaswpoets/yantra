<?php
namespace Plugins\yantraadmin\Controllers;

use Core\LowCode\LowCode;
use Core\LowCode\LowCodeModule;
use Exception;
use Plugins\yantraadmin\Services\UserSessionService;
use System\Request;
use System\Response;

class BaseController
{
    /**
     * @throws Exception
     */
    public function __construct()
    {
        add_filter('view_path',function ($path){
            return 'Plugins/yantraadmin/Views';
        });
        LowCode::init();
    }

    protected function view($name="index",array $data=[]): bool|string
    {
        $view_path = apply_filter('view_path','');
        $file = "$view_path/{$name}.php";
        if (!file_exists($file)) {
            return '';
        }
        extract($data);
        ob_start();
        include $file;
        return ob_get_clean();
    }
}
