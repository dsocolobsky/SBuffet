<?php

class Usuario {

    public $id;
    public $username;
    public $password;
    public $nombre;
    public $apellido;
    public $saldo;
    public $ultimaCompra;
    public $activo;
    
    function __construct($username, $app, $database) {
        $usuario = $database->usuarios->where('username', $username)->fetch();

        $this->id       = $usuario['id'];
        $this->username = $usuario['username'];
        $this->password = $usuario['password'];
        $this->nombre   = $usuario['nombre'];
        $this->apellido = $usuario['apellido'];
        $this->saldo    = $usuario['saldo'];
        
        $this->update($app, $database);
    }
    
    function update($app, $database) {
        $usuario = $database->usuarios[$this->id];
        $compras = $database->pedidos('usuario', $this->id)->order("hora_compra DESC");
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