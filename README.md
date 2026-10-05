# 🛒 Proyecto Tienda Web - DWES

Proyecto de comercio electrónico básico desarrollado para la asignatura de **Desarrollo Web en Entorno Servidor (DWES)** - 2º DAW.

---

## 👥 Integrantes del Grupo
* **Adrián Trujillo** ([@adriitr16](https://github.com/adriitr16))
* **Nayara Bastida** ([@nayarabastida6](https://github.com/nayarabastida6))

---

## 🛠️ Tecnologías Utilizadas
* **Lenguaje del Servidor:** PHP 8.x 
* **Base de Datos:** MySQL / MariaDB
* **Cliente de BD:** MySQL Command Line Client
* **Control de Versiones:** Git & GitHub

---

## 🗄️ Esquema de la Base de Datos

El sistema gestiona una tienda online sencilla compuesta por **4 tablas principales**:

1. **`CLIENTE`**: Registro e identificación de usuarios.
2. **`PRODUCTOS`**: Catálogo de artículos disponibles y control de stock.
3. **`CARRITO`**: Almacenamiento temporal de los productos seleccionados por el cliente antes de la compra.
4. **`PEDIDO`**: Histórico de compras realizadas por los clientes.

### 📐 Modelo Relacional ($N:M$)
* **Carrito ($N:M$):** Un cliente puede tener varios productos en su carrito y un producto puede estar en los carritos de varios clientes.
* **Pedido ($N:M$):** Un cliente puede realizar múltiples pedidos y un producto puede figurar en múltiples compras pasadas.

---
