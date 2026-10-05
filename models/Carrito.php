<?php
// Creamos la clase carrito que contiene id, id_cliente, id_producto, cantidad y producto

    class Carrito {
        private int $id;
        private int $id_cliente;
        private int $id_producto;
        private int $cantidad;
        private ?Producto $producto;

        // Constructor
        public function __construct(int $id, int $id_cliente, int $id_producto, int $cantidad, ?Producto $producto) {
            $this->id = $id;
            $this->id_cliente = $id_cliente;
            $this->id_producto = $id_producto;
            $this->cantidad = $cantidad;
            $this->producto = $producto;
        }

        // Getters
        public function getId() {
            return $this->id;
        } 

        public function getIdCliente() {
            return $this->id_cliente;
        }

        public function getIdProducto() {
            return $this->id_producto;
        }

        public function getCantidad() {
            return $this->cantidad;
        }

        public function getProducto(): ?Producto {
            return $this->producto;
        }

        // Setters
        public function setCantidad(int $cantidad) {
            if ($cantidad < 0) {
                throw new InvalidArgumentException("La cantidad no puede ser negativa.");
            }
            $this->cantidad = $cantidad;
        }

        public function setProducto(?Producto $producto) {
            $this->producto = $producto;
        }

        public function agregarProducto(Producto $producto, int $cantidad) {
            if ($cantidad <= 0) {
                throw new InvalidArgumentException("La cantidad debe ser mayor que cero.");
            }
            $this->producto = $producto;
            $this->cantidad += $cantidad;
        }

        public function eliminarProducto(Producto $producto, int $cantidad) {
            // Verificamos si el cliente solo quiere elminar una cantidad específica del producto
            if ($cantidad <= 0) {
                throw new InvalidArgumentException("La cantidad debe ser mayor que cero.");
            }
            if ($this->producto->getId() === $producto->getId()) {
                if ($this->cantidad < $cantidad) {
                    throw new InvalidArgumentException("No se puede eliminar más cantidad de la que hay en el carrito.");
                }
                $this->cantidad -= $cantidad;
                if ($this->cantidad === 0) {
                    $this->producto = null; // Si la cantidad llega a cero, eliminamos el producto del carrito
                }
            } else {
                throw new InvalidArgumentException("El producto no está en el carrito.");
            }
        }
    }
?>