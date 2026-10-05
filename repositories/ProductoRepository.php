<?php
require_once("models/Producto.php");
require_once __DIR__ . '/../db.php';

class ProductoRepository {
    private $db;

    public function __construct() {
        $this->db = db::connect();
    }

    public function crearProducto(string $nombre, string $descripcion, int $stock, float $precio): bool {
        $stmt = $this->db->prepare('INSERT INTO PRODUCTO (NOMBRE, DESCRIPCION, STOCK, PRECIO) VALUES (?, ?, ?, ?)');
        return $stmt->execute([$nombre, $descripcion, $stock, $precio]);
    }

    // Obtener todos los productos
    public function obtenerTodos(): array {
        $stmt = $this->db->query('SELECT * FROM PRODUCTO');
        return $stmt->fetchAll();
    }

    // Obtiene el detalle de un producto específico
    public function obtenerPorId(int $idProducto): ?array {
        $stmt = $this->db->prepare('SELECT * FROM PRODUCTO WHERE ID_PRODUCTO = ?');
        $stmt->execute([$idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        return $producto ?: null;
    }

    // Reduce las unidades disponibles tras confirmar un pedido
    public function descontarStock(int $idProducto, int $cantidad): bool {
        $stmt = $this->db->prepare('UPDATE PRODUCTO SET STOCK = STOCK - ? WHERE ID_PRODUCTO = ? AND STOCK >= ?');
        return $stmt->execute([$cantidad, $idProducto, $cantidad]);
    }

    // Verifica si el stock disponible cubre la cantidad solicitada (o la suma acumulada)
    public function comprobarStock(int $idProducto, int $cantidadDeseada): bool {
        $stmt = $this->db->prepare('SELECT STOCK FROM PRODUCTO WHERE ID_PRODUCTO = ?');
        $stmt->execute([$idProducto]);
        $stockActual = $stmt->fetchColumn();

        return ($stockActual !== false) && ($stockActual >= $cantidadDeseada);
    }
}

?>