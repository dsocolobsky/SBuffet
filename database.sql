CREATE DATABASE IF NOT EXISTS sbuffet;
USE sbuffet;

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS usuarios;
CREATE TABLE IF NOT EXISTS usuarios (
    id INT NOT NULL PRIMARY KEY,
    username VARCHAR(128),
    nombre VARCHAR(64),
    apellido VARCHAR(128),
    saldo DECIMAL(6, 2)
);

DROP TABLE IF EXISTS productos;
CREATE TABLE IF NOT EXISTS productos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(256),
    precio DECIMAL(5, 2),
    disponible BOOLEAN
);

DROP TABLE IF EXISTS pedidos;
CREATE TABLE IF NOT EXISTS pedidos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    usuario INT NOT NULL,
    producto INT NOT NULL,
    hora_compra TIMESTAMP
);

SET FOREIGN_KEY_CHECKS=1;

ALTER TABLE pedidos ADD CONSTRAINT fk_pedido_usuario FOREIGN KEY (usuario) REFERENCES usuarios(id);
ALTER TABLE pedidos ADD CONSTRAINT fk_pedido_producto FOREIGN KEY (producto) REFERENCES productos(id);

INSERT IGNORE INTO usuarios (id, username, nombre, apellido, saldo) VALUES (1, "dsocolobsky", "Dylan", "Socolobsky", 500);

INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("hamburguesa", 20, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("milanesa", 30, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("pizza", 15.25, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("pollo", 40, false);

