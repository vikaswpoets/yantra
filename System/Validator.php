<?php

namespace System;

class Validator
{
    public function validate($data, $rules)
    {
        $errors = [];
        foreach ($rules as $field => $rule) {
            if ($rule === 'required' && empty($data[$field])) {
                $errors[$field] = "{$field} is required.";
            }
        }
        return $errors;
    }
}
