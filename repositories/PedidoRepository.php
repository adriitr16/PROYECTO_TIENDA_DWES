<?php
require_once("models/Carrito.php");
require_once __DIR__ . '/../db.php';

class PedidoRepository {
    private PDO $db;

    public function __construct() {
        $this->db = db::connect();
    }

    // Inserta una línea de compra en la tabla PEDIDO al confirmar el pago
    public function crearPedido(int $idCliente, int $idProducto, int $cantidad): bool {
        $stmt = $this->db->prepare('INSERT INTO PEDIDO (ID_CLIENTE, ID_PRODUCTO, CANTIDAD) VALUES (?, ?, ?)');
        return $stmt->execute([$idCliente, $idProducto, $cantidad]);
    }

    // Devuelve el historial de compras pasadas de un usuario para su perfil
    public function obtenerPedidosPorCliente(int $idCliente): array {
        $sql = 'SELECT p.ID_PEDIDO, p.FECHA, p.CANTIDAD, pr.NOMBRE, pr.PRECIO, (pr.PRECIO * p.CANTIDAD) AS TOTAL 
                FROM PEDIDO p 
                JOIN PRODUCTO pr ON p.ID_PRODUCTO = pr.ID_PRODUCTO 
                WHERE p.ID_CLIENTE = ? 
                ORDER BY p.FECHA DESC';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$idCliente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cancela un pedido comprobando que pertenezca al cliente activo y devuelve los datos del pedido eliminado
    public function cancelarPedido(int $idPedido, int $idCliente): ?array {
        // 1. Obtener la información del pedido antes de borrarlo
        $stmt = $this->db->prepare('SELECT ID_PRODUCTO, CANTIDAD FROM PEDIDO WHERE ID_PEDIDO = ? AND ID_CLIENTE = ?');
        $stmt->execute([$idPedido, $idCliente]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($pedido) {
            // 2. Eliminar la fila del pedido
            $stmtDelete = $this->db->prepare('DELETE FROM PEDIDO WHERE ID_PEDIDO = ? AND ID_CLIENTE = ?');
            $stmtDelete->execute([$idPedido, $idCliente]);
            return $pedido;
        }

        return null;
    }
}