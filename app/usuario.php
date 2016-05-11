<?php

class Usuario {
    
    private $id;
    private $username;
    private $nombre;
    private $apellido;
    private $saldo;
    
    function __construct($usuario, $app, $database) {
        $this->id       = $usuario['id'];
        $this->username = $usuario['username'];
        $this->nombre   = $usuario['nombre'];
        $this->apellido = $usuario['apellido'];
        $this->saldo    = $usuario['saldo'];
    }
    
    function getId() {
        return $this->id;
    }
    
    function getUsername() {
        return $this->username;
    }
    
    function getNombre() {
        return $this->nombre;
    }
    
    function getApellido() {
        return $this->apellido;
    }
    
    function getSaldo() {
        return $this->saldo;
    }
    
    function getUltimaCompra() {
        return "hoy";
    }
    
    function activo() {
        return true;
    }
    
}

?>