<?php
class Pedido {
    private $id;
    private $fecha;
    private $total;
    private $productos;

    public function __construct($id, $fecha, $total) {
        $this->id = $id;
        $this->fecha = $fecha;
        $this->total = $total;
        $this->productos = [];
    }

    public function getId() {
        return $this->id;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getTotal() {
        return $this->total;
    }

    public function getProductos() {
        return $this->productos;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function setProductos($productos) {
        $this->productos = $productos;
    }
}
?>