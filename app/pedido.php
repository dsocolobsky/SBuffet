<?php

class Pedido {
    
    public $id;
    public $usuario;
    public $producto;
    public $hora_compra;
    
    public $nombreProducto;
    public $precio;

    public $codigo_usuario;

    public $horario;
    
    function __construct($pedido, $app, $database) {
        $this->id = $pedido['id'];
        $this->usuario = $pedido['usuario'];
        $this->producto = $pedido['producto'];
        $this->hora_compra = $pedido['hora_compra'];
        
        $producto = $database->productos[$this->producto];
        $this->nombreProducto = $producto['nombre'];
        $this->precio = $producto['precio'];

        $this->nombre_usuario = $database->usuarios[$this->usuario]['nombre'];

        $entregaTime = $pedido['horario_entrega'];
        $dia = date('d', strtotime($entregaTime));
        $diaHoy = date('d');
        $tomorrow = new DateTime('tomorrow 7pm');
        $diaTomorrow = $tomorrow->format('d');
        $hora = date('G', strtotime($entregaTime));

        if ($dia == $diaHoy) {
            $this->horario = "Hoy ";
        } else if ($dia == $diaTomorrow) {
            $this->horario = "Mañana ";
        } else {
            $this->horario = "RETRASADO";
            return;
        }

        if ($hora == "11") {
            $this->horario = $this->horario . " mediodia";
        } else if ($hora == "19") {
            $this->horario = $this->horario . " noche";
        } else {
            $this->horario = "ERROR";
        }
    }
    
}

?>