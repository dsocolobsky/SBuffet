CREATE DATABASE IF NOT EXISTS sbuffet;
USE sbuffet;

DROP TABLE IF EXISTS productos;

CREATE TABLE IF NOT EXISTS productos (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(256),
    precio DECIMAL(5, 2),
    disponible BOOLEAN
);

INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("hamburguesa", 20, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("milanesa", 30, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("pizza", 15.25, true);
INSERT IGNORE INTO productos (nombre, precio, disponible) VALUES ("pollo", 40, false);