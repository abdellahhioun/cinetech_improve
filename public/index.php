<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

$controllerName = $_GET['controller'] ?? 'movie';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

$controllerClass = 'App\\Controllers\\' . ucfirst($controllerName) . 'Controller';

if (class_exists($controllerClass)) {
    $controller = new $controllerClass();
    if (method_exists($controller, $action)) {
        $id ? $controller->{$action}($id) : $controller->{$action}();
    } else {
        echo 'Action not found.';
    }
} else {
    echo 'Controller not found.';
}
