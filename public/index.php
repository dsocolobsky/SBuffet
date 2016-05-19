<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../app/producto.php';
require '../app/usuario.php';
require '../app/pedido.php';
require '../app/codigo.php';

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
$database->debug = true;

session_start();

require '../app/functions.php';
require '../app/router.php';

$app->run();
