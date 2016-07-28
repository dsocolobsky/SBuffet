<?php

$app->get('/', function ($request, $response) use($app, $database) {
})->add($index);

$app->get('/logout', function ($request, $response) use($app) {
    logOut();
    return $response->withStatus(302)->withHeader('Location', '/login');
})->add($debeLoggearse);

$app->get('/login', function ($request, $response) use($app) {
    return $this->view->render($response, 'login.html');
})->add($noDebeLoggearse);

$app->post('/login', function ($request, $response) use($app, $database) {
    $usuario  = $request->getParsedBody()['usuario'];
    $password = $request->getParsedBody()['password'];
    
    $res = comprobarLogin($usuario, $password, $app, $database);
     
    if ($res > 0) {
        $_SESSION['id'] = $usuario;
    }
    
    return $response->write($res);
})->add($noDebeLoggearse);

$app->get('/registro', function ($request, $response) use($app) {
    return $this->view->render($response, 'registro.html');
})->add($noDebeLoggearse);

$app->post('/registro', function ($request, $response) use($app, $database) {
    $datos = $request->getParsedBody()['datos'];
    return registrarse($datos, $app, $database);
})->add($noDebeLoggearse);

$app->get('/menu', function ($request, $response) use($app, $database) {
    $usuario = new Usuario($_SESSION['id'], $app, $database);
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'menu.html', array(
        'usuario' => $usuario,
        'productos' => $productos
    ));
})->add($debeLoggearse);

$app->get('/usuario', function ($request, $response) use($app, $database) {
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return $response->withStatus(302)->withHeader('Location', '/login');
    }
    
    $usuario = new Usuario($_SESSION['id'], $app, $database);
    $historial = obtenerHistorialPedidosUsuario($_SESSION['id'], $app, $database);
    
    return $this->view->render($response, 'usuario.html', array (
        'usuario' => $usuario,
        'historial' => $historial
    ));
})->add($debeLoggearse);

$app->get('/productos', function ($request, $response) use($app, $database) {
    $usuario = new Usuario($_SESSION['id'], $app, $database);
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'productos.html', array (
        'usuario' => $usuario,
        'productos' => $productos
    ));
})->add($debeSerAdmin);

$app->post('/compra', function ($request, $response) use($app, $database) {  
    $productos = $request->getParsedBody()['productos'];
    realizarPedido($productos, $app, $database);
})->add($debeLoggearse);

$app->get('/pedidos', function ($request, $response) use($app, $database) {
    $pendientes = obtenerPedidosActivos($app, $database);
    $entregados = obtenerPedidosEntregados($app, $database);
    
    return $this->view->render($response, 'pedidos.html', array (
        'pendientes' => $pendientes,
        'entregados' => $entregados
    ));
})->add($debeSerAdmin);

$app->get('/usuarios', function ($request, $response) use($app, $database) {
    $usuarios = obtenerUsuarios($app, $database);
    $codigos = obtenerCodigos($app, $database);
    
    return $this->view->render($response, 'usuarios.html', array (
        'usuarios' => $usuarios,
        'codigos' => $codigos
    ));
})->add($debeSerAdmin);

$app->post('/listo', function ($request, $response) use($app, $database) {
    $pedido = $request->getParsedBody()['id'];
    
    // Indica que el pedido esta listo y entregado, pero lo guarda
    // en la lista de pedidos entregados del Buffet
    $database->pedidos[$pedido]->update(array (
        "activo" => false,
        "guardado" => true,
    ));
})->add($debeSerAdmin);

$app->post('/borrarpedido', function ($request, $response) use($app, $database) {    
    $pedido = $request->getParsedBody()['id'];
    
    // Borra el pedido de la lista de entregados del buffet
    // (Pero no del historial del usuario)
    $database->pedidos[$pedido]->update(array (
        "guardado" => false,
    ));
})->add($debeSerAdmin);

$app->post('/codigo', function ($request, $response) use($app, $database) {    
    return generarCodigo($app, $database);
})->add($debeSerAdmin);

$app->post('/borrarcodigo', function ($request, $response) use($app, $database) {    
    $codigo = $request->getParsedBody()['codigo'];
    return $database->codigos[$codigo]->delete();
})->add($debeSerAdmin);

$app->post('/obtenersaldo', function ($request, $response) use($app, $database) {
    $id = $request->getParsedBody()['id'];
    $username = $database->usuarios[$id]['username'];
    $usuario = new Usuario($username, $app, $database);
    
    return $this->view->render($response, 'cargarsaldo.html', array(
        'saldo' => $usuario->saldo,
        'usuario' => $usuario
    ));
})->add($debeLoggearse);

$app->post('/cargarsaldo', function ($request, $response) use($app, $database) {
    $username = $request->getParsedBody()['usuario'];
    $saldo = $request->getParsedBody()['saldo'];

    $usuario = new Usuario($username, $app, $database);

    cargarSaldo($usuario, $saldo, $app, $database);
    return $response->withStatus(302)->withHeader('Location', '/usuarios');
})->add($debeSerAdmin);

?>