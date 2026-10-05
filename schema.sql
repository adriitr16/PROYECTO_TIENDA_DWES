-- 1. Creación de la base de datos si no existe
CREATE DATABASE IF NOT EXISTS tienda_db 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE tienda_db;

-- 2. Limpieza de tablas previas (por si volvéis a reejecutar el script)
DROP TABLE IF EXISTS PEDIDO;
DROP TABLE IF EXISTS CARRITO;
DROP TABLE IF EXISTS PRODUCTO;
DROP TABLE IF EXISTS CLIENTE;

-- 3. Tabla CLIENTE
CREATE TABLE CLIENTE (
    ID_CLIENTE INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE VARCHAR(100) NOT NULL,
    EMAIL VARCHAR(100) NOT NULL UNIQUE,
    CONTRASEÑA VARCHAR(255) NOT NULL,
    TELEFONO VARCHAR(20)
) ENGINE=InnoDB;

-- 4. Tabla PRODUCTO
CREATE TABLE PRODUCTO (
    ID_PRODUCTO INT AUTO_INCREMENT PRIMARY KEY,
    NOMBRE VARCHAR(100) NOT NULL,
    DESCRIPCION TEXT,
    STOCK INT NOT NULL DEFAULT 0,
    PRECIO DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB;

-- 5. Tabla CARRITO (Relación N:M entre CLIENTE y PRODUCTO)
CREATE TABLE CARRITO (
    ID_CARRITO INT AUTO_INCREMENT PRIMARY KEY,
    ID_CLIENTE INT NOT NULL,
    ID_PRODUCTO INT NOT NULL,
    CANTIDAD INT NOT NULL DEFAULT 1,
    CONSTRAINT fk_carrito_cliente FOREIGN KEY (ID_CLIENTE) 
        REFERENCES CLIENTE(ID_CLIENTE) ON DELETE CASCADE,
    CONSTRAINT fk_carrito_producto FOREIGN KEY (ID_PRODUCTO) 
        REFERENCES PRODUCTO(ID_PRODUCTO) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. Tabla PEDIDO (Relación N:M entre CLIENTE y PRODUCTO)
CREATE TABLE PEDIDO (
    ID_PEDIDO INT AUTO_INCREMENT PRIMARY KEY,
    ID_CLIENTE INT NOT NULL,
    ID_PRODUCTO INT NOT NULL,
    CANTIDAD INT NOT NULL DEFAULT 1,
    FECHA DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (ID_CLIENTE) 
        REFERENCES CLIENTE(ID_CLIENTE) ON DELETE CASCADE,
    CONSTRAINT fk_pedido_producto FOREIGN KEY (ID_PRODUCTO) 
        REFERENCES PRODUCTO(ID_PRODUCTO) ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 7. Insert de productos de prueba
INSERT INTO PRODUCTO (NOMBRE, DESCRIPCION, STOCK, PRECIO) VALUES
    -> ('Ratón Gaming 16000 DPI', 'Ratón óptico ergonómico con botones programables e iluminación RGB.', 25, 29.90),
    -> ('Monitor 24 Full HD', 'Panel IPS 144Hz 1ms especial para gaming sin parpadeos.', 10, 159.99),
    -> ('Auriculares 7.1 Surround', 'Auriculares con micrófono omnidireccional y cancelación de ruido.', 18, 39.50),
    -> ('Teclado Mecánico RGB', 'Teclado con switches Red lineales y chasis de aluminio resistente.', 15, 59.99),
    -> ('Alfombrilla XL Gamer', 'Superficie de tela de alta precisión con bordes cosidos reforzados.', 40, 14.95),
    -> ('Disco Duro SSD 1TB NVMe', 'Disco de almacenamiento ultra rápido PCIe 4.0 hasta 7000 MB/s.', 30, 89.90),
    -> ('Memoria RAM 16GB DDR4', 'Kit de 2x8GB a 3200MHz CL16 con disipador de calor.', 22, 45.00),
    -> ('Procesador Octa Core', 'Procesador de 8 núcleos y 16 hilos hasta 4.7GHz.', 12, 219.00),
    -> ('Tarjeta Gráfica 8GB', 'GPU de arquitectura moderna ideal para jugar a 1080p en Ultra.', 6, 329.99),
    -> ('Fuente de Alimentación 650W', 'Fuente con certificación 80 Plus Bronze y cables mallados.', 14, 54.90),
    -> ('Caja PC ATX Cristal', 'Torre con frontal en rejilla y 3 ventiladores ARGB incluidos.', 8, 69.99),
    -> ('Refrigeración Líquida 240mm', 'Kit AIO con radiador doble y bomba silenciosa.', 11, 79.90),
    -> ('Webcam Full HD 1080p', 'Cámara con micrófono integrado y tapa de privacidad.', 20, 24.99),
    -> ('Silla Gaming Ergonómica', 'Silla reclinable con cojín lumbar y reposabrazos 3D.', 5, 149.00),
    -> ('Micrófono Condensador USB', 'Micrófono con patrón cardioide y trípode de mesa incluido.', 16, 34.99),
    -> ('Soporte Monitor Doble', 'Brazo neumático para 2 monitores de 17 a 32 pulgadas.', 13, 42.50),
    -> ('HUB USB-C 7 en 1', 'Adaptador multiport con puerto HDMI 4K, lectores SD y USB 3.0.', 25, 21.99),
    -> ('Router WiFi 6 Dual Band', 'Router de alta velocidad para conexiones de fibra simétricas.', 9, 64.90),
    -> ('Disco Duro Externo 2TB', 'Almacenamiento portátil USB 3.0 para copias de seguridad.', 17, 62.00),
    -> ('Pasta Térmica Alto Rendimiento', 'Jeringa de 4g de compuesto térmico de alta conductividad.', 50, 7.99);