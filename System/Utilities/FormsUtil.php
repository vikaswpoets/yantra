<?php
namespace System\Utilities;

class FormsUtil {
    private $formConfig;

    public function __construct($formConfig) {
        $this->formConfig = $formConfig;
    }

    public function render() {
        $html = '<form>';
        foreach ($this->formConfig as $field) {
            switch ($field['type']) {
                case 'text':
                    $html .= FormFieldsUtil::inputText($field);
                    break;
                case 'email':
                    $html .= FormFieldsUtil::inputEmail($field);
                    break;
                case 'date':
                    $html .= FormFieldsUtil::inputDate($field);
                    break;
                case 'textarea':
                    $html .= FormFieldsUtil::textarea($field);
                    break;
                case 'select':
                    $html .= FormFieldsUtil::select($field);
                    break;
                case 'checkbox':
                    $html .= FormFieldsUtil::checkbox($field);
                    break;
                case 'radio':
                    $html .= FormFieldsUtil::radio($field);
                    break;
                case 'file':
                    $html .= FormFieldsUtil::inputFile($field);
                    break;
                default:
                    $_html = "<p>Unsupported form element type: {$field['type']}</p>";
                    $html .= apply_filter("form_field_{$field['type']}",$_html,$field);
            }
        }
        $html .= '</form>';
        return $html;
    }
}