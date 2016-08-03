<?php

class Pedido {
    
    public $id;
    public $usuario;
    public $producto;
    public $hora_compra;
    
    public $nombreProducto;
    public $precio;

    public $codigo_usuario;
    
    function __construct($pedido, $app, $database) {
        $this->id = $pedido['id'];
        $this->usuario = $pedido['usuario'];
        $this->producto = $pedido['producto'];
        $this->hora_compra = $pedido['hora_compra'];
        
        $producto = $database->productos[$this->producto];
        $this->nombreProducto = $producto['nombre'];
        $this->precio = $producto['precio'];

        $this->codigo_usuario = $database->usuarios[$this->usuario]['codigo'];
    }
    
}

?>