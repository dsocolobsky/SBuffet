<?php

class Usuario {

    public $username;
    public $codigo;
    public $password;
    public $nombre;
    public $apellido;
    public $saldo;
    public $ultimaCompra;
    public $activo;
    
    function __construct($username, $app, $database) {
        $usuario = $database->usuarios[$username];

        $this->username = $usuario['username'];
        $this->codigo   = $usuario['codigo'];
        $this->password = $usuario['password'];
        $this->nombre   = $usuario['nombre'];
        $this->apellido = $usuario['apellido'];
        $this->saldo    = $usuario['saldo'];
        
        $this->update($app, $database);
    }
    
    function update($app, $database) {
        $usuario = $database->usuarios[$this->username];
        $compras = $database->pedidos('usuario', $this->username)->order("hora_compra DESC");
        $this->ultimaCompra = $compras[1]['hora_compra'];
        
        foreach ($compras as $compra) {
            if($compra['activo'] == true) {
                $this->activo = true;
                return;
            }
        }
        
        $this->activo = false;
    }
    
}

?>