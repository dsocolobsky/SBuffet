<?php

class Producto {
    
    public $id;
    public $nombre;
    public $precio;
    public $disponible;
    
    function __construct($producto, $app, $database) {
        $this->id = $producto['id'];
        $this->nombre = $producto['nombre'];
        $this->precio = $producto['precio'];
        $this->disponible = $producto['disponible'];
    }
        
}

?>