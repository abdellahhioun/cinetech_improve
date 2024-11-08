<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/config.php';

use Ahiou\CinetechImprove\Controllers\MovieController;

$controller = new MovieController();

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($path) {
    case '/':
        $controller->index();
        break;
    
    case '/search':
        $controller->search();
        break;
    
    case '/api/favorite':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            header('Content-Type: application/json');
            echo json_encode($controller->toggleFavorite($data['movieId']));
        }
        break;
    
    case '/favorites':
        $controller->favorites();
        break;
    
    default:
        if (preg_match('/^\/movie\/(\d+)$/', $path, $matches)) {
            $controller->details($matches[1]);
        } else {
            http_response_code(404);
            echo '404 Not Found';
        }
        break;
}
