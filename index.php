<?php

session_start();
ini_set('display_errors', 1);
require_once 'app/bootstrap.php';

function __autoload($className)
{
    $classControllerFilePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'app' .
                                DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $className . '.php';
    $classModelFilePath = $_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . 'app' .
                                DIRECTORY_SEPARATOR . 'models' . DIRECTORY_SEPARATOR . $className . '.php';
    if (file_exists($classControllerFilePath)) {
        require_once $classControllerFilePath;
        return true;
    } elseif (file_exists($classModelFilePath)) {
        require_once $classModelFilePath;
        return true;
    }
    return false;
}

