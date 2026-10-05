<?php
require_once("models/Cliente.php");
require_once __DIR__ . '/../db.php';

class ClienteRepository {
    private $db;

    public function __construct() {
        $this->db = db::connect();
    }

    // Insertar el cliente aplicando MD5 a la contraseña directamente en la BD
    public function crearCliente(string $nombre, string $email, string $password, string $telefono): bool {
        $stmt = $this->db->prepare('INSERT INTO CLIENTE (NOMBRE, EMAIL, CONTRASEÑA, TELEFONO) VALUES (?, ?, MD5(?), ?)');
        return $stmt->execute([$nombre, $email, $password, $telefono]);
    }

    // Obtener cliente por su email
    public function obtenerPorEmail(string $email): ?array {
        $stmt = $this->db->prepare('SELECT * FROM CLIENTE WHERE EMAIL = ?');
        $stmt->execute([$email]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }

    // Validar las credenciales (Email y Contraseña) mediante MD5
    public function autenticar(string $email, string $password): ?array {
        $stmt = $this->db->prepare('SELECT * FROM CLIENTE WHERE EMAIL = ? AND CONTRASEÑA = MD5(?)');
        $stmt->execute([$email, $password]);
        $cliente = $stmt->fetch();
        return $cliente ?: null;
    }
}
?>