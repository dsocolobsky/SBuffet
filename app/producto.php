<?php

class Producto {
    
    public $id;
    public $nombre;
    public $precio;
    public $stock;
    public $disponibilidad;
    
    function __construct($producto, $app, $database) {
        $this->id = $producto['id'];
        $this->nombre = $producto['nombre'];
        $this->precio = $producto['precio'];
        $this->stock = $producto['stock'];
        $this->disponibilidad = $producto['disponibilidad'];
    }
        
}

?>