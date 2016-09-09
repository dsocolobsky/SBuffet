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
    $username = $database->usuarios[$_SESSION['id']]['username'];
    $usuario = new Usuario($username, $app, $database);
    $productos = obtenerProductos($app, $database);
    
    return $this->view->render($response, 'productos.html', array (
        'usuario' => $usuario,
        'productos' => $productos
    ));
})->add($debeSerAdmin);

$app->post('/compra', function ($request, $response) use($app, $database) {  
    $productos = $request->getParsedBody()['productos'];
    $horario = $request->getParsedBody()['horario'];
    $val = realizarPedido($productos, $horario, $app, $database);
    return $response->write($val);
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

$app->get('/administrador', function ($request, $response) use($app, $database) {
    $usuarios = obtenerUsuarios($app, $database);
    $codigos = obtenerCodigos($app, $database);
    
    return $this->view->render($response, 'administrador.html', array (
    ));
})->add($debeSerAdmin);

$app->get('/agregarproducto', function ($request, $response) use($app, $database) {
    return $this->view->render($response, 'nuevoproducto.html', array (
    ));
})->add($debeSerAdmin);

$app->post('/agregarproducto', function ($request, $response) use($app, $database) {
    $nombre = $request->getParsedBody()['nombre'];
    $precio = $request->getParsedBody()['precio'];
    $disponibilidad = $request->getParsedBody()['disponibilidad'];
    
    agregarProducto($nombre, $precio, $disponibilidad, $app, $database);

    return $response->withStatus(302)->withHeader('Location', '/productos');
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

$app->post('/productodisponible', function ($request, $response) use($app, $database) {
    $id = $request->getParsedBody()['id'];
    disponibilidadProducto($id, true, $app, $database);
})->add($debeSerAdmin);

$app->post('/productonodisponible', function ($request, $response) use($app, $database) {
    $id = $request->getParsedBody()['id'];
    disponibilidadProducto($id, false, $app, $database);
})->add($debeSerAdmin);

$app->post('/borrarproducto', function ($request, $response) use($app, $database) {    
    $id = $request->getParsedBody()['id'];
    return $database->productos[$id]->delete();
})->add($debeSerAdmin);

$app->post('/cambiarpassword', function ($request, $response) use($app, $database) {
    $passOriginal = $request->getParsedBody()['passOriginal'];
    $passNueva = $request->getParsedBody()['passNueva'];
    $passNueva2 = $request->getParsedBody()['passNueva2'];

    $val = cambiarPassword($passOriginal, $passNueva, $passNueva2, $app, $database);
    return $response->write($val);
})->add($debeLoggearse);

$app->post('/borrarusuario', function ($request, $response) use($app, $database) {    
    $usuario = $request->getParsedBody()['id'];
    return $database->usuarios[$usuario]->delete();
})->add($debeSerAdmin);

?>