<?php

$app->get('/', function ($request, $response) use($app, $database) {
    $usuario = obtenerUsuario(1, $app, $database);
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'menu.html', array(
        'usuario' => $usuario,
        'productos' => $productos
    ));
});

$app->get('/login', function ($request, $response) use($app) {
    return $this->view->render($response, 'login.html');
});

$app->get('/registro', function ($request, $response) use($app) {
    return $this->view->render($response, 'login.html');
});

$app->get('/usuario', function ($request, $response) use($app, $database) {
    $usuario = obtenerUsuario('1', $app, $database);
    $historial = obtenerHistorialPedidosUsuario('1', $app, $database);
    
    return $this->view->render($response, 'usuario.html', array (
        'usuario' => $usuario,
        'historial' => $historial
    ));
});

$app->get('/productos', function ($request, $response) use($app, $database) {
    $usuario = obtenerUsuario(1, $app, $database);
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'productos.html', array (
        'usuario' => $usuario,
        'productos' => $productos
    ));
});

$app->post('/compra', function ($request, $response) use($app, $database) {    
    $productos = $request->getParsedBody()['productos'];
    realizarPedido($productos, $app, $database);
});

$app->get('/usuarios', function ($request, $response) use($app, $database) {
    $usuarios = obtenerUsuarios($app, $database);
    
    return $this->view->render($response, 'usuarios.html', array (
        'usuarios' => $usuarios
    ));
});

?>