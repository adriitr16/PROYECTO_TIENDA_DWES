<?php
    // Creamos la clase pedido que contiene id, fecha, total, productos y ademas se basa en:
    // la tabla pedido de schema.sql
    class Pedido {
        private int $id;
        private string $fecha;
        private float $total;
        private array $productos; // Array de objetos Producto

        // Constructor
        public function __construct(int $id, string $fecha, float $total, array $productos) {
            $this->id = $id;
            $this->fecha = $fecha;
            $this->total = $total;
            $this->productos = $productos;
        }

        // Getters
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

        // Setters
        public function setFecha(string $fecha) {
            $this->fecha = $fecha;
        }

        public function setTotal(float $total) {
            if ($total < 0) {
                throw new InvalidArgumentException("El total no puede ser negativo.");
            }
            $this->total = $total;
        }

        public function setProductos(array $productos) {
            foreach ($productos as $producto) {
                if (!$producto instanceof Producto) {
                    throw new InvalidArgumentException("Todos los elementos del array deben ser instancias de la clase Producto.");
                }
            }
            $this->productos = $productos;
        }
    }
?>   