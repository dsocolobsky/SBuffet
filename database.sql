CREATE DATABASE IF NOT EXISTS sbuffet;
USE sbuffet;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios (
    username VARCHAR(128) NOT NULL PRIMARY KEY,
    codigo INT AUTO_INCREMENT,
    password VARCHAR(256),
    nombre VARCHAR(128),
    saldo DECIMAL(6, 2),
    UNIQUE(codigo)
);

DROP TABLE IF EXISTS productos;
CREATE TABLE IF NOT EXISTS productos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(256),
    precio DECIMAL(5, 2),
    disponibilidad BOOLEAN,
    stock BOOLEAN
);

DROP TABLE IF EXISTS pedidos;
CREATE TABLE IF NOT EXISTS pedidos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(128) NOT NULL,
    producto INT NOT NULL,
    hora_compra TIMESTAMP,
    horario_entrega TIMESTAMP,
    activo BOOLEAN,
    guardado BOOLEAN
);

DROP TABLE IF EXISTS codigos;
CREATE TABLE IF NOT EXISTS codigos (
    codigo VARCHAR(4) NOT NULL PRIMARY KEY,
    emision TIMESTAMP  
);

SET FOREIGN_KEY_CHECKS=1;

ALTER TABLE pedidos ADD CONSTRAINT fk_pedido_usuario FOREIGN KEY (usuario) REFERENCES usuarios(username);
ALTER TABLE pedidos ADD CONSTRAINT fk_pedido_producto FOREIGN KEY (producto) REFERENCES productos(id);

INSERT IGNORE INTO usuarios (username, password, nombre, saldo) VALUES ("admin", "$2y$10$WVTAtbZ.PgR.IF04oD.vmOq/7nmd/o6A7jpqPvx/LsIeiyKlnsJE2", "Admin", 600);
INSERT IGNORE INTO usuarios (username, password, nombre, saldo) VALUES ("dsocolobsky", "$2y$10$kDjLqzAQ8zvmZAGxaJYtausy2IxLAIbutg2.3RNQQuk8fRDnGtonO", "Dylan Socolobsky", 100);

/*INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("hamburguesa", 20, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("milanesa", 30, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("pizza", 15.25, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("pollo", 40, false);*/
