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
    $tabla_pedidos = $database->pedidos('activo', true)->order('horario_entrega ASC, usuario ASC');
    
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

function realizarPedido($productos, $horario, $app, $database) {
    $hoy = new DateTime();
    $dia = $hoy->format('l');
    $hora = $hoy->format('H');
    if (esFinde($dia, $hora)) {
        return -3;
    }

    $saldo = $database->usuarios[$_SESSION['id']]['saldo'];
    $precio_total = 0.00;

    $puederealizarlo = true;
    $horarioEntrega = null;
    $estaDisponible = true;

    foreach ($productos as $producto) {
        $disponibilidad = $database->productos[$producto]['disponibilidad'];
        if ($disponibilidad == false) {
            $estaDisponible = false;
            break;
        }
    }

    if ($horario === "mediodia") {
        if(date('H') < 11) {
            $horarioEntrega = new DateTime('today 11am');
        } else {
            $horarioEntrega = new DateTime('tomorrow 11am');
        }
    } else if ($horario === "noche") {
        if(date('H') < 11) {
            $horarioEntrega = new DateTime('today 7pm');
        } else {
            if ($estaDisponible === true) {
                $horarioEntrega = new DateTime('today 7pm');
            } else {
                $horarioEntrega = new DateTime('tomorrow 7pm');
            }
        }
    }
    
    foreach ($productos as $producto) {
        $precio = $database->productos[$producto]['precio'];
        $precio_total += $precio;
    }
    
    $nsaldo = $saldo - $precio_total;
    if ($nsaldo < 0.00) {
        return -1;
    }
    
    $affected = $database->usuarios[$_SESSION['id']]->update(array (
        "saldo" => $saldo - $precio_total
    ));
    
    foreach ($productos as $producto) {
        $pedido = $database->pedidos()->insert(array (
                "usuario" => $_SESSION['id'],
                "producto" => $producto,
                "hora_compra" => new NotORM_Literal("NOW()"),
                "horario_entrega" => $horarioEntrega,
                "activo" => true,
                "guardado" => false
            ));
    }
}

function esFinde($dia, $hora) {
    if ($dia == 'Saturday' || $dia == 'Sunday') {
        return true;
    } else if ($dia == 'Friday' && $hora > 20) {
        return true;
    }

    return false;
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
    
    $entrada = $database->usuarios()->insert(array (
        'username' => $datos['usuario'],
        'password' => password_hash($datos['password'], PASSWORD_DEFAULT),
        'nombre' => $datos['nombre'],
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
    $nsaldo = $database->usuarios[$usuario->username]->update(array (
        "saldo" => $usuario->saldo + $saldo
    ));

    return $nsaldo;
}

function disponibilidadProducto($id, $stock, $app, $database) {
    $nproducto = $database->productos[$id]->update(array (
        "stock" => $stock
    ));
}

function agregarProducto($nombre, $precio, $disponibilidad, $app, $database) {
    if ($disponibilidad == null) {
        $disponibilidad = false;
    } else {
        $disponibilidad = true;
    }

    $producto = $database->productos()->insert(array (
                "nombre" => $nombre,
                "precio" => $precio,
                "disponibilidad" => $disponibilidad,
                "stock" => true
            ));
}

function cambiarPassword($passOriginal, $passNueva, $passNueva2, $app, $database) {
    $username = $database->usuarios[$_SESSION['id']]['username'];
    $user = new Usuario($username, $app, $database);

    if(!password_verify($passOriginal, $user->password)) {
        return -1;
    } else if ($passNueva !== $passNueva2) {
        return 0;
    } else {
        $nusuer = $database->usuarios[$_SESSION['id']]->update(array (
            "password" => password_hash($passNueva, PASSWORD_DEFAULT)
        ));

        return 1;
    }
}

function logOut() {
    if (isset($_SESSION['id']) || !empty($_SESSION['id'])) {
        unset($_SESSION['id']);
    }
}

?>