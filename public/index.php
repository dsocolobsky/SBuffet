<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../app/producto.php';

$config['displayErrorDetails'] = true;
$config['base_url'] = "app/";

$app = new \Slim\App(["settings" => $config]);
$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../app/templates', [
        'cache' => 'false',
        'debug' => 'true',
    ]);
    
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;  
};

$database_data = "mysql:dbname=sbuffet;host=127.0.0.1";
$pdo = new PDO($database_data, "root", "root");
$structure = new NotORM_Structure_Discovery($pdo, $cache = null, $foreign = '%s');
$database = new NotORM($pdo, $structure);

$app->get('/', function ($request, $response) use($app, $database) {
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'menu.html', array(
        'productos' => $productos
    ));
});

$app->get('/login', function ($request, $response) use($app) {
    return $this->view->render($response, 'login.html');
});

$app->get('/registro', function ($request, $response) use($app) {
    return $this->view->render($response, 'login.html');
});

function obtenerProductos($app, $database) {
    $productos = array();
    $tabla_productos = $database->productos();
    
    foreach ($tabla_productos as $producto) {
        array_push($productos, new Producto($producto, $app, $database));
    }
    
    return $productos;
}

$app->run();