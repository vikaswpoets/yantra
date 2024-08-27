<?php
namespace Modules {
    use System\Model;

    class StudentModule extends Model
    {
        public function __construct()
        {
            parent::__construct('user','id');
        }


        // Student-specific functionality
    }
}

