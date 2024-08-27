<?php
namespace System\Utilities;

class FormFieldsUtil {

    private static function filter(string $html, string $type, string $name, string $id, array $params): string
    {
        return apply_filter("form_field_$type", $html, $name, $id, $params);
    }

    public static function inputText(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $placeholder = $params['placeholder'] ?? '';
        $value = $params['value'] ?? '';
        $class = $params['class'] ?? 'form-control';
        $html = "
        <div class='form-group'>
            <label for='{$id}'>{$label}</label>
            <input type='text' class='{$class}' id='{$id}' name='{$name}' placeholder='{$placeholder}' value='{$value}'>
        </div>";
        return self::filter($html, 'text', $name, $id, $params);
    }

    public static function inputEmail(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $placeholder = $params['placeholder'] ?? '';
        $value = $params['value'] ?? '';
        $class = $params['class'] ?? 'form-control';
        $html = "
        <div class='form-group'>
            <label for='{$id}'>{$label}</label>
            <input type='email' class='{$class}' id='{$id}' name='{$name}' placeholder='{$placeholder}' value='{$value}'>
        </div>";
        return self::filter($html, 'email', $name, $id, $params);
    }

    public static function inputDate(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $value = $params['value'] ?? '';
        $min = $params['min'] ?? '';
        $max = $params['max'] ?? '';
        $class = $params['class'] ?? 'form-control';
        $html = "
        <div class='form-group'>
            <label for='{$id}'>{$label}</label>
            <input type='date' class='{$class}' id='{$id}' name='{$name}' value='{$value}' min='{$min}' max='{$max}'>
        </div>";
        return self::filter($html, 'date', $name, $id, $params);
    }

    public static function textarea(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $placeholder = $params['placeholder'] ?? '';
        $value = $params['value'] ?? '';
        $rows = $params['rows'] ?? 3;
        $class = $params['class'] ?? 'form-control';
        $html = "
        <div class='form-group'>
            <label for='{$id}'>{$label}</label>
            <textarea class='{$class}' id='{$id}' name='{$name}' placeholder='{$placeholder}' rows='{$rows}'>{$value}</textarea>
        </div>";
        return self::filter($html, 'textarea', $name, $id, $params);
    }

    public static function select(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $options = $params['options'] ?? [];
        $selectedValue = $params['value'] ?? '';
        $class = $params['class'] ?? 'form-control';
        $optionsHtml = '';
        foreach ($options as $option) {
            $selected = ($selectedValue == $option['value']) ? 'selected' : '';
            $optionsHtml .= "<option value='{$option['value']}' {$selected}>{$option['label']}</option>";
        }
        $html = "
        <div class='form-group'>
            <label for='{$id}'>{$label}</label>
            <select class='{$class}' id='{$id}' name='{$name}'>
                {$optionsHtml}
            </select>
        </div>";
        return self::filter($html, 'select', $name, $id, $params);
    }

    public static function checkbox(string $name, string $id, array $params): string
    {
        $value = $params['value'] ?? '';
        $checked = $params['checked'] ?? false;
        $class = $params['class'] ?? 'form-check-input';
        $checkedAttr = $checked ? 'checked' : '';
        $html = "
        <div class='form-check'>
            <input type='checkbox' class='{$class}' id='{$id}' name='{$name}' value='{$value}' {$checkedAttr}>
        </div>";
        return self::filter($html, 'checkbox', $name, $id, $params);
    }

    public static function radio(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $value = $params['value'] ?? '';
        $checked = $params['checked'] ?? false;
        $class = $params['class'] ?? 'form-check-input';
        $checkedAttr = $checked ? 'checked' : '';
        $html = "
        <div class='form-check'>
            <input type='radio' class='{$class}' id='{$id}' name='{$name}' value='{$value}' {$checkedAttr}>
            <label class='form-check-label' for='{$id}'>{$label}</label>
        </div>";
        return self::filter($html, 'radio', $name, $id, $params);
    }

    public static function inputFile(string $name, string $id, array $params): string
    {
        $label = $params['label'] ?? '';
        $accept = $params['accept'] ?? '';
        $class = $params['class'] ?? 'form-control';
        $html = "
        <div class='form-group'>
            <label for='{$id}'>{$label}</label>
            <input type='file' class='{$class}' id='{$id}' name='{$name}' accept='{$accept}'>
        </div>";
        return self::filter($html, 'file', $name, $id, $params);
    }
}

