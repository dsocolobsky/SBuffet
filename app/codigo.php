<?php

class Codigo {
    
    private $codigo;
    private $emision;
    
    function __construct($codigo) {
        $this->codigo = $codigo['codigo'];
        $this->emision = $codigo['emision']; 
    }
    
    function getCodigo() {
        return $this->codigo;
    }
    
    function getEmision() {
        return $this->emision;
    }
    
}

?>