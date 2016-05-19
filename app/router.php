<?php

$app->get('/', function ($request, $response) use($app, $database) {
    $usuario = new Usuario(1, $app, $database);
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'menu.html', array(
        'usuario' => $usuario,
        'productos' => $productos
    ));
});

$app->get('/login', function ($request, $response) use($app) {
    return $this->view->render($response, 'login.html');
});

$app->post('/login', function ($request, $response) use($app, $database) {
    $usuario  = $request->getParsedBody()['usuario'];
    $password = $request->getParsedBody()['password'];
    
    $res = comprobarLogin($usuario, $password, $app, $database);
     
    if ($res == 1) {
        $_SESSION['id'] = $usuario;
    }
    
    return $response->write($res);
});

$app->get('/registro', function ($request, $response) use($app) {
    return $this->view->render($response, 'registro.html');
});

$app->post('/registro', function ($request, $response) use($app, $database) {
    $datos = $request->getParsedBody()['datos'];
    return registrarse($datos, $app, $database);
});

$app->get('/usuario', function ($request, $response) use($app, $database) {
    $usuario = new Usuario('1', $app, $database);
    $historial = obtenerHistorialPedidosUsuario('1', $app, $database);
    
    return $this->view->render($response, 'usuario.html', array (
        'usuario' => $usuario,
        'historial' => $historial
    ));
});

$app->get('/productos', function ($request, $response) use($app, $database) {
    $usuario = new Usuario(1, $app, $database);
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

$app->get('/pedidos', function ($request, $response) use($app, $database) {
    $pendientes = obtenerPedidosActivos($app, $database);
    $entregados = obtenerPedidosEntregados($app, $database);
    
    return $this->view->render($response, 'pedidos.html', array (
        'pendientes' => $pendientes,
        'entregados' => $entregados
    ));
});

$app->get('/usuarios', function ($request, $response) use($app, $database) {
    $usuarios = obtenerUsuarios($app, $database);
    $codigos = obtenerCodigos($app, $database);
    
    return $this->view->render($response, 'usuarios.html', array (
        'usuarios' => $usuarios,
        'codigos' => $codigos
    ));
});

$app->post('/listo', function ($request, $response) use($app, $database) {    
    $pedido = $request->getParsedBody()['id'];
    pedidoListo($pedido, $app, $database);
});

$app->post('/borrarpedido', function ($request, $response) use($app, $database) {    
    $pedido = $request->getParsedBody()['id'];
    
    $database->pedidos[$pedido]->update(array (
        "guardado" => false,
    ));
});

$app->post('/codigo', function ($request, $response) use($app, $database) {    
    return generarCodigo($app, $database);
});

$app->post('/borrarcodigo', function ($request, $response) use($app, $database) {    
    $codigo = $request->getParsedBody()['codigo'];
    return $database->codigos[$codigo]->delete();
});

$app->post('/obtenersaldo', function ($request, $response) use($app, $database) {
    $id = $request->getParsedBody()['id'];
    $usuario = new Usuario($id, $app, $database);
    
    return $this->view->render($response, 'cargarsaldo.html', array(
        'saldo' => $usuario->saldo,
    ));
});

$app->post('/cargarsaldo', function ($request, $response) use($app, $database) {
    $saldo = $request->getParsedBody()['saldo']; 
    return $saldo;
});

?>