<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../vendor/autoload.php';
require '../app/producto.php';
require '../app/usuario.php';
require '../app/pedido.php';

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

session_start();

function obtenerUsuario($id, $app, $database) {
    $usuario = $database->usuarios[$id];
    return new Usuario($usuario, $app, $database);
}

function obtenerUsuarios($app, $database) {
    $usuarios = array();
    $tabla_usuarios = $database->usuarios();
    
    foreach ($tabla_usuarios as $usuario) {
        array_push($usuarios, new Usuario($usuario, $app, $database));
    }
    
    return $usuarios;
}

function obtenerProductos($app, $database) {
    $productos = array();
    $tabla_productos = $database->productos();
    
    foreach ($tabla_productos as $producto) {
        array_push($productos, new Producto($producto, $app, $database));
    }
    
    return $productos;
}

function obtenerPedidosActivos($app, $database) {
    $pedidos = array();
    $tabla_pedidos = $database->pedidos()->where('activo', true)->order('usuario ASC');
    
    foreach ($tabla_pedidos as $pedido) {
        array_push($pedidos, new Pedido($pedido, $app, $database));
    }
    
    return $pedidos;
}

function obtenerPedidosEntregados($app, $database) {
    $pedidos = array();
    $tabla_pedidos = $database->pedidos()->where('guardado', true)->order('hora_compra DESC');

    foreach ($tabla_pedidos as $pedido) {
        array_push($pedidos, new Pedido($pedido, $app, $database));
    }
    
    return $pedidos;
}

function obtenerHistorialPedidosUsuario($usuario, $app, $database) {
    $pedidos = array();
    $tabla_pedidos = $database->pedidos()->where('usuario', $usuario)->order("hora_compra DESC");
    
    foreach ($tabla_pedidos as $pedido) {
        array_push($pedidos, new Pedido($pedido, $app, $database));
    }
    
    return $pedidos;
}

function realizarPedido($productos, $app, $database) {
    $saldo = $database->usuarios[1]['saldo'];
    $precio_total = 0.00;
    
    foreach ($productos as $producto) {
        $precio = $database->productos[$producto]['precio'];
        $precio_total += $precio;
    }
    
    $nsaldo = $saldo - $precio_total; 
    if ($nsaldo < 0.00) {
        return;
    }
    
    $affected = $database->usuarios[1]->update(array (
        "saldo" => $saldo - $precio_total
    ));
    
    foreach ($productos as $producto) {
        $pedido = $database->pedidos()->insert(array (
                "usuario" => 1,
                "producto" => $producto,
                "hora_compra" => new NotORM_Literal("NOW()"),
                "activo" => true,
                "guardado" => false
            ));
    }
}

function pedidoListo($pedido, $app, $database) {
    $affected = $database->pedidos[$pedido]->update(array (
        "activo" => false,
        "guardado" => true,
    ));
}

function borrarPedido($pedido, $app, $database) {
    $affected = $database->pedidos[$pedido]->update(array (
        "guardado" => false,
    ));
}

require '../app/router.php';

$app->run();
