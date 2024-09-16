<?php

namespace App;

class Blade
{
    public function getTemplate($name, array $data = [], string $extension = '.php')
    {
        $name = $_SERVER['DOCUMENT_ROOT'] . '/templates/' . $name . $extension;
        $result = '';

        if (!is_readable($name)) {
            return $result;
        }

        ob_start();
        extract($data);
        require $name;

        return ob_get_clean();
    }
}