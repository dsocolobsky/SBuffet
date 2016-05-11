<?php

class Usuario {
    
    private $id;
    private $username;
    private $nombre;
    private $apellido;
    private $saldo;
    private $ultimaCompra;
    private $activo;
    
    function __construct($usuario, $app, $database) {
        $this->id       = $usuario['id'];
        $this->username = $usuario['username'];
        $this->nombre   = $usuario['nombre'];
        $this->apellido = $usuario['apellido'];
        $this->saldo    = $usuario['saldo'];
        
        $this->update($app, $database);
    }
    
    function update($app, $database) {
        $usuario = $database->usuarios[$this->id];
        $compras = $database->pedidos()->where('usuario', $this->id)->order("hora_compra DESC");
        $this->ultimaCompra = $compras[1]['hora_compra'];
        
        foreach ($compras as $compra) {
            if($compra['activo'] == true) {
                $this->activo = true;
                return;
            }
        }
        
        $this->activo = false;
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
        return $this->ultimaCompra;
    }
    
    function getActivo() {
        return $this->activo;
    }
    
}

?>