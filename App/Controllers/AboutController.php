<?php

namespace Controllers {

    use System\Controller;
    use System\Request;
    use System\Response;

    class AboutController extends Controller
    {
        public function index(Request $request, Response $response): void
        {
            $response->render([]);
        }
    }
}