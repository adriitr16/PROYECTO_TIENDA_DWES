<?php
    // Creamos la clase cliente que contiene id, nombre, email, telefono, state
    class Cliente {
        private int $id;
        private string $nombre;
        private string $email;
        private string $telefono;
        protected $state = 0;       // 0 = inactivo, 1 = activo

        // Constructor
        public function __construct(int $id, string $nombre, string $email, string $telefono) {
            $this->id = $id;
            $this->nombre = $nombre;
            $this->email = $email;
            $this->telefono = $telefono;
            $this->state = 1; // Si se crea un cliente, se considera activo por defecto
        }

        // Getters
        public function getId() {
            return $this->id;
        }

        public function getNombre() {
            return $this->nombre;
        }

        public function getEmail() {
            return $this->email;
        }

        public function getTelefono() {
            return $this->telefono;
        }

        public function getState() {
            return $this->state;
        }

        // Setters
        public function setNombre(string $nombre) {
            $this->nombre = $nombre;
        }

        public function setEmail(string $email) {
            $this->email = $email;
        }

        public function setTelefono(string $telefono) {
            $this->telefono = $telefono;
        }
    }
    
?>