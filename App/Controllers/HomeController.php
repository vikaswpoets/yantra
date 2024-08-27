<?php

namespace Controllers {

    use System\Controller;

    class HomeController extends Controller
    {
        public function index()
        {
            try {
                $user_m = $this->model('StudentModule');
                //$user_m->insert(array('username '=>'vishal','password'=>'12345','contact'=>'8600338356'));
                $users = $user_m->list();

            }
            catch (\Exception $e) {
                echo $e->getMessage();
            }
            $this->view->render("App/Views/home", ['title' => 'Home Page']);
        }
    }
}
