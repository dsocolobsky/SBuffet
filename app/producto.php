<?php

class Producto {
    
    private $id;
    private $nombre;
    private $precio;
    private $disponible;
    
    function __construct($producto, $app, $database) {
        $this->id = $producto['id'];
        $this->nombre = $producto['nombre'];
        $this->precio = $producto['precio'];
        $this->disponible = $producto['disponible'];
    }
    
    function getNombre() {
        return $this->nombre;
    }
    
    function getPrecio() {
        return $this->precio;
    }
    
    function getDisponible() {
        return $this->disponible;
    }
    
}

?>