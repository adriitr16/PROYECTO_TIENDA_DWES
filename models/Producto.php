<?php
    // Creamos la clase producto que contiene id, nombre, descripcion, stock, precio

    class Producto {
        private int $id;
        private string $nombre;
        private string $descripcion;
        private int $stock;
        private float $precio;

        // Constructor
        public function __construct(int $id, string $nombre, string $descripcion, int $stock, float $precio) {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->descripcion = $descripcion;
            $this->stock = $stock;
            $this->precio = $precio;
        }

        // Getters
        public function getId() {
            return $this->id;
        }

        public function getNombre() {
            return $this->nombre;
        }

        public function getDescripcion() {
            return $this->descripcion;
        }

        public function getStock() {
            return $this->stock;
        }

        public function getPrecio() {
            return $this->precio;
        }

        // Setters
        public function setNombre(string $nombre) {
            $this->nombre = $nombre;
        }

        public function setDescripcion(string $descripcion) {
            $this->descripcion = $descripcion;
        }

        public function setStock(int $stock) {
            if ($stock < 0) {
                throw new InvalidArgumentException("El stock no puede ser negativo.");
            }
            $this->stock = $stock;
        }

        public function setPrecio(float $precio) {
            if ($precio < 0) {
                throw new InvalidArgumentException("El precio no puede ser negativo.");
            }
            $this->precio = $precio;
        }
    }
?>