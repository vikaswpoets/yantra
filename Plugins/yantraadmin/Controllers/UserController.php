<?php
namespace Plugins\yantraadmin\Controllers;

use Exception;
use Plugins\yantraadmin\Services\UserSessionService;
use System\Request;
use System\Response;

class UserController
{
    protected UserSessionService $userSession;

    /**
     * @throws Exception
     */
    public function __construct($loginRequired = false,$permissions =[])
    {
        $this->userSession = new UserSessionService();
        if($loginRequired)
            $this->loginCheck($permissions);
        add_filter('view_path',function ($path){
            return 'Plugins/yantraadmin/Views/';
        });
    }

    /**
     * @throws Exception
     */
    protected function loginCheck($permissions =[]): void
    {
        if(!$this->userSession->loginCheck()){
            $this->userSession->logout();
            http_response_code(302);
            $login_url = site_url('admin');
            header("Location: $login_url");
            exit();
        }
        if (count($permissions)>0){
            foreach ($permissions as $permission){
                if(!$this->hasPermission($permission)){
                    $this->userSession->logout();
                    http_response_code(401);
                    header("Location: login");
                    exit();
                }
            }
        }
    }

    protected function hasPermission($permission): bool
    {
        return true;
    }

    protected function getPermissions(): array
    {
        return array();
    }
}
