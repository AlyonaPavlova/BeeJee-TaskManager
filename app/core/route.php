<?php

class Route
{
    static function start()
    {
        // Контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';

        $routes = explode('/', $_SERVER['REQUEST_URI']);

        // Получаем имя контроллера
        if ( !empty($routes[1]))
        {
            $controller_name = $routes[1];
        }

        // Получаем имя экшена
        if ( !empty($routes[2]) )
        {
            $action_name = $routes[2];
        }

        // Добавляем префиксы
        $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        // Подцепляем файл с классом модели

        $model_file = strtolower($model_name).'.php';
        $model_path = "app/models/".$model_file;
        if(file_exists($model_path))
        {
            include "app/models/".$model_file;
        }

        include "app/models/safemysql.php";

        // Подключаем модель списка с задачами
        include "app/models/model_list.php";

        // Подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "app/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            include "app/controllers/".$controller_file;
        }
        else {}

        // Создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;

        if(method_exists($controller, $action))
        {
            // Вызываем действие контроллера
            $controller->$action();
        }
        else {}
    }
}