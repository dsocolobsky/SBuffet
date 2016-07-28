<?php

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
    $tabla_pedidos = $database->pedidos('activo', true)->order('usuario ASC');
    
    foreach ($tabla_pedidos as $pedido) {
        array_push($pedidos, new Pedido($pedido, $app, $database));
    }
    
    return $pedidos;
}

function obtenerPedidosEntregados($app, $database) {
    $pedidos = array();
    $tabla_pedidos = $database->pedidos('guardado', true)->order('hora_compra DESC');

    foreach ($tabla_pedidos as $pedido) {
        array_push($pedidos, new Pedido($pedido, $app, $database));
    }
    
    return $pedidos;
}

function obtenerHistorialPedidosUsuario($usuario, $app, $database) {
    $pedidos = array();
    $tabla_pedidos = $database->pedidos('usuario', $usuario)->order("hora_compra DESC");
    
    foreach ($tabla_pedidos as $pedido) {
        array_push($pedidos, new Pedido($pedido, $app, $database));
    }
    
    return $pedidos;
}

function realizarPedido($productos, $app, $database) {
    $saldo = $database->usuarios[$_SESSION['id']]['saldo'];
    $precio_total = 0.00;
    
    foreach ($productos as $producto) {
        $precio = $database->productos[$producto]['precio'];
        $precio_total += $precio;
    }
    
    $nsaldo = $saldo - $precio_total; 
    if ($nsaldo < 0.00) {
        return;
    }
    
    $affected = $database->usuarios[$_SESSION['id']]->update(array (
        "saldo" => $saldo - $precio_total
    ));
    
    foreach ($productos as $producto) {
        $pedido = $database->pedidos()->insert(array (
                "usuario" => $_SESSION['id'],
                "producto" => $producto,
                "hora_compra" => new NotORM_Literal("NOW()"),
                "activo" => true,
                "guardado" => false
            ));
    }
}

function comprobarLogin($usuario, $password, $app, $database) {
    $user = new Usuario($usuario, $app, $database);

    if (empty($user)) {
        return 0;
    } else if (password_verify($password, $user->password)) {
        if ($user->username == 'admin') {
            return 2;
        }
        return 1;
    } else {
        return -1;
    }
}

function registrarse($datos, $app, $database) {
    if (!$database->codigos[$datos['codigo']]) {
        return "no existe el codigo";
    }
    
    $usuarios = $database->usuarios('username', $datos['usuario']);
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
    
    // Si pudimos registrar el usuario, borrar el codigo utilizado
    if ($entrada) {
        $status = $database->codigos[$datos['codigo']]->delete();
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

function cargarSaldo($usuario, $saldo, $app, $database) {
    if ($saldo <= 0) {
        return "ERROR";
    }

    $nsaldo = $database->usuarios[$usuario->username]->update(array (
        "saldo" => $usuario->saldo + $saldo
    ));

    return $nsaldo;
}

function logOut() {
    if (isset($_SESSION['id']) || !empty($_SESSION['id'])) {
        unset($_SESSION['id']);
    }
}

?>