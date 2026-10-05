<?php
class Carrito {
    private $productos;

    public function __construct() {
        $this->productos = [];
    }

    public function getProductos() {
        return $this->productos;
    }

    public function agregarProducto($producto) {
        $this->productos[] = $producto;
    }

    public function eliminarProducto($indice) {
        if (isset($this->productos[$indice])) {
            unset($this->productos[$indice]);
            $this->productos = array_values($this->productos); // Reindexar el array
        }
    }

    public function vaciarCarrito() {
        $this->productos = [];
    }

}
?>