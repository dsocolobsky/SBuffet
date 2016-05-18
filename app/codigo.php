<?php

class Codigo {
    
    public $codigo;
    public $emision;
    
    function __construct($codigo) {
        $this->codigo = $codigo['codigo'];
        $this->emision = $codigo['emision']; 
    }
    
}

?>