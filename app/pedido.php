<?php

class Pedido {
    
    private $id;
    private $usuario;
    private $producto;
    private $hora_compra;
    
    private $nombreProducto;
    private $precio;
    
    function __construct($pedido, $app, $database) {
        $this->id = $pedido['id'];
        $this->usuario = $pedido['usuario'];
        $this->producto = $pedido['producto'];
        $this->hora_compra = $pedido['hora_compra'];
        
        $producto = $database->productos[$this->producto];
        $this->nombreProducto = $producto['nombre'];
        $this->precio = $producto['precio'];
    }
    
    function getId() {
        return $this->id;
    }
    
    function getUsuario() {
        return $this->usuario;
    }
    
    function getProducto() {
        return $this->producto;
    }
    
    function getNombreProducto() {
        return $this->nombreProducto;
    }
    
    function getPrecio() {
        return $this->precio;
    }
    
    function getHora_compra() {
        return $this->hora_compra;
    }
    
}


?>