<?php

class Pedido {
    
    private $id;
    private $usuario;
    private $producto;
    private $hora_compra;
    
    function __construct($pedido, $app, $database) {
        $this->id = $pedido['id'];
        $this->usuario = $pedido['usuario'];
        $this->producto = $pedido['producto'];
        $this->hora_compra = $pedido['hora_compra'];
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
    
    function getHora_compra() {
        return $this->hora_compra;
    }
    
}


?>