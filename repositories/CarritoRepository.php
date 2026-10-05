<?php
require_once("models/Producto.php");
require_once __DIR__ . '/../db.php';

class CarritoRepository {
    private $db;

    public function __construct() {
        $this->db = db::connect();
    }

    // Lista todos los productos, cantidades y precios que tiene un cliente en su carrito activo
    public function obtenerCarritoPorCliente(int $idCliente): array {
        $sql = 'SELECT c.ID_CARRITO, c.ID_PRODUCTO, p.NOMBRE, p.PRECIO, c.CANTIDAD, (p.PRECIO * c.CANTIDAD) AS SUBTOTAL 
                FROM CARRITO c 
                JOIN PRODUCTO p ON c.ID_PRODUCTO = p.ID_PRODUCTO 
                WHERE c.ID_CLIENTE = ?';
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Añade un ítem o incrementa la cantidad si ya existe en el carrito
    public function agregarProducto(int $idCliente, int $idProducto, int $cantidad = 1): bool {
        // Verificar si el producto ya está en el carrito
        $stmt = $this->db->prepare('SELECT ID_CARRITO, CANTIDAD FROM CARRITO WHERE ID_CLIENTE = ? AND ID_PRODUCTO = ?');
        $stmt->execute([$idCliente, $idProducto]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($item) {
            // Si ya existe, actualizamos sumando la nueva cantidad
            $nuevaCantidad = $item['CANTIDAD'] + $cantidad;
            return $this->actualizarCantidad($item['ID_CARRITO'], $nuevaCantidad);
        } else {
            // Si no existe, lo insertamos
            $stmt = $this->db->prepare('INSERT INTO CARRITO (ID_CLIENTE, ID_PRODUCTO, CANTIDAD) VALUES (?, ?, ?)');
            return $stmt->execute([$idCliente, $idProducto, $cantidad]);
        }
    }

    // Modifica la cantidad de unidades desde la vista del carrito
    public function actualizarCantidad(int $idCarrito, int $nuevaCantidad): bool {
        if ($nuevaCantidad <= 0) {
            return $this->eliminarProducto($idCarrito);
        }

        $stmt = $this->db->prepare('UPDATE CARRITO SET CANTIDAD = ? WHERE ID_CARRITO = ?');
        return $stmt->execute([$nuevaCantidad, $idCarrito]);
    }

    // Elimina un producto específico del carrito
    public function eliminarProducto(int $idCarrito): bool {
        $stmt = $this->db->prepare('DELETE FROM CARRITO WHERE ID_CARRITO = ?');
        return $stmt->execute([$idCarrito]);
    }

    // Borra todos los ítems del carrito de un cliente cuando completa el pago
    public function vaciarCarrito(int $idCliente): bool {
        $stmt = $this->db->prepare('DELETE FROM CARRITO WHERE ID_CLIENTE = ?');
        return $stmt->execute([$idCliente]);
    }
}