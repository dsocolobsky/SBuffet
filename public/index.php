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

function obtenerCodigos($app, $database) {
    $codigos = array();
    $tabla_codigos = $database->codigos()->order('emision DESC');
    
    foreach ($tabla_codigos as $codigo) {
        array_push($codigos, new Codigo($codigo, $app, $database));
    }
    
    return $codigos;
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

function comprobarLogin($usuario, $password, $app, $database) {
    $usuario_db = $database->usuarios()->where('username', $usuario);
    if (!$usuario_db) {
        return false;
    }
    
    foreach($usuario_db as $userdb) {
        if ($userdb['password'] == $password) {
            return true;
        }
    }
    
    return false;
}

function registrarse($datos, $app, $database) {
    if (!$database->codigos[$datos['codigo']]) {
        return "no existe el codigo";
    }
    
    $usuarios = $database->usuarios()->where('username', $datos['usuario']); 
    foreach($usuarios as $u) {
        return "el usuario ya existe";
    }
    
    $nombre = explode(" ", $datos['nombre']);
    
    // TODO: ARREGLAR NOMBRES Y APELLIDOS
    $entrada = $database->usuarios()->insert(array (
        'username' => $datos['usuario'],
        'password' => password_hash($datos['password'], PASSWORD_DEFAULT),
        'nombre' => $nombre[0],
        'apellido' => $nombre[1],
        'saldo' => 0.00
    ));
    
    if ($entrada) {
        $status = borrarCodigo($datos['codigo'], $app, $database);
    }
        
    return "usuario creado";
}

function generarCodigo($app, $database) {
    do {
        $codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 4);
    } while ($database->codigos[$codigo]);
    
    $entrada = $database->codigos()->insert(array (
        "codigo" => $codigo,
        "emision" => new NotORM_Literal("NOW()"),
    ));
    
    return $codigo;
}

function borrarCodigo($codigo, $app, $database) {
    $status = $database->codigos[$codigo]->delete();
    return $status;
}

function obtenerSaldo($id, $app, $database) {
    $usuario = $database->usuarios[$id];
    return $usuario['saldo'];
}

require '../app/router.php';

$app->run();
