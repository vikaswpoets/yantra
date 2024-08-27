<?php

namespace System;

use Exception;

class Controller
{
    public function __construct()
    {

    }

    /**
     * @throws Exception
     */
    public function model($modelName):Model
    {
        $modelClass = "Modules\\" . ucfirst($modelName);
        if (class_exists($modelClass)) {
            return new $modelClass();
        } else {
            throw new Exception("Model {$modelClass} not found");
        }
    }
}
