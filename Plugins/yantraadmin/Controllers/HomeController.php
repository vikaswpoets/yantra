<?php

namespace Plugins\yantraadmin\Controllers;

use Core\LowCode\LowCode;
use Core\LowCode\ShortcodeManager;
use Exception;
use JetBrains\PhpStorm\NoReturn;
use System\Request;
use System\Response;

class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    #[NoReturn] public function index(Request $request, Response $response): void
    {

        try {
            /*echo "<pre>";
            print_r(LowCode::$modules);
            print_r(LowCode::$shortcodes);
            echo "</pre>";*/
            //$cnt = ShortcodeManager::parse("[module sample name='vikas']this is test[/module]");
            //$cnt2 = ShortcodeManager::parse("[module  sample {main} name='vikas' test email='vikas@gmail.com' mobile={865236598}]this is test[/module]");
            //$cnt3 = ShortcodeManager::parse("[module  \"main\" name='vikas']this is test[/module]");
            //$cnt4 = ShortcodeManager::parse("[module  {test} name='vikas']this is test[/module]");
            //echo  ShortcodeManager::render("[module.sample name='vikas' test email='vikas@gmail.com' mobile={865236598}/]");
            //LowCode::print_r(LowCode::$modules);
            //[module.sample.smallest name='vikas' test email='vikas@gmail.com' mobile={865236598} /]

            ob_start();
            ?>
            [if main=" 'admin' == 'admin' "]
                <p>Welcome, Admin!</p>
            [/if]
            [else main="1 == 1"]
            <p>Welcome, Editor!</p>
            [/else]
            [else]
            <p>Welcome, Guest!</p>
            [/else]


            [math.circleArea main="5" set="y" /]

            <p>y= [get y /]</p>
            [math.absolute main="{y}" set="a" /]

            <p>x = [get x /]</p>

            <p>y= [get y /]</p>
            <p>A= [get a /]</p>

            [set x="14" /]

            [while main="{x} > 5"]
                [set.increment main="x"  x="-1" /]
                [if main="{x} == 7"]
                   <p>n= [get x /]</p>
                    [break /]
                [/if]
                [else]
                    <p> X = [get x /]</p>
                [/else]
            [/while]
            <hr/>
            [module.sample.get_set_samples /]
            <?php
            $content = ob_get_clean();
            echo  ShortcodeManager::parse( $content);
            $response->exit();

//            $content = 'Example 1: [set key="value" some_attr="123" /]
//Example 2: [get param="something"]some content[/get]
//Example 3: [set some_attr="another value"]more  content[/set]';
//            $compiled = LowCode::process($content);
//            $response->meta('content',$compiled);
//            //$lc->registerModule('sample');

        } catch (Exception $e) {
            echo $e->getMessage();
        }
        //$content = $this->view('sample');
        //$response->render();
    }
}