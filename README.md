# 🛒 Proyecto Tienda Web - DWES

Proyecto de comercio electrónico desarrollado para la asignatura de **Desarrollo Web en Entorno Servidor (DWES)** — 2º DAW. La aplicación implementa un flujo dinámico completo que abarca desde la autenticación de usuarios hasta la gestión en tiempo real del stock, carrito interactivo y cancelación de compras.

## 👥 Integrantes del Grupo

* **Adrián Trujillo** ([@adriitr16](https://github.com/adriitr16))
* **Nayara Bastida** ([@nayarabastida6](https://github.com/nayarabastida6))

## 🛠️ Tecnologías y Requisitos

* **Lenguaje de Servidor:** PHP 8.x (Servidor Integrado)
* **Acceso a Datos:** PDO (PHP Data Objects) con Sentencias Preparadas
* **Base de Datos:** MySQL 8.0
* **Cliente de Base de Datos:** MySQL 8.0 Command Line Client
* **Seguridad:** Hashing de contraseñas mediante `MD5()` en consultas SQL
* **Front-end:** HTML5 semántico y CSS3 modular (Flexbox, CSS Grid y diseño responsive)
* **Control de Versiones:** Git & GitHub

## 📁 Arquitectura y Estructura del Proyecto

El proyecto sigue un patrón **MVC Modular Simplificado** articulado mediante un **Front Controller** (`mainController.php`) que gestiona las peticiones y renderiza el contenido dinámicamente sobre una plantilla central (`mainView.phtml`).

```text
PROYECTO_TIENDA_DWES/
├── .env                         # Variables de entorno (credenciales BD)
├── db.php                       # Conector Singleton PDO a MySQL
├── index.php                    # Punto de entrada principal
├── controllers/
│   └── mainController.php       # Enrutador central y lógica de negocio
├── repositories/                # Capa de Acceso a Datos (DAO)
│   ├── ClienteRepository.php
│   ├── ProductoRepository.php
│   ├── CarritoRepository.php
│   └── PedidoRepository.php
├── views/
│   └── mainView.phtml           # Plantilla vista única modular
└── public/
    └── css/
        └── style.css            # Hoja de estilos principal
```

## 🗄️ Esquema y Modelo de Base de Datos

La base de datos consta de 4 tablas relacionales diseñadas para garantizar la integridad referencial y la gestión en tiempo real de transacciones:

* **CLIENTE:** Almacena los usuarios registrados (`ID_CLIENTE`, `NOMBRE`, `EMAIL`, `CONTRASEÑA`, `TELEFONO`).
* **PRODUCTO:** Mantiene el catálogo y existencias (`ID_PRODUCTO`, `NOMBRE`, `DESCRIPCION`, `STOCK`, `PRECIO`).
* **CARRITO:** Cesta temporal por cliente (`ID_CARRITO`, `ID_CLIENTE`, `ID_PRODUCTO`, `CANTIDAD`).
* **PEDIDO:** Histórico de ventas procesadas (`ID_PEDIDO`, `ID_CLIENTE`, `ID_PRODUCTO`, `CANTIDAD`, `FECHA`).

### 📐 Modelo Relacional ($N:M$)

* **Cliente – Producto (CARRITO):** Relación de muchos a muchos ($N:M$). Permite que un cliente tenga múltiples productos guardados y que un mismo producto figure en diferentes carritos.
* **Cliente – Producto (PEDIDO):** Relación de muchos a muchos ($N:M$). Registra las transacciones históricas confirmadas entre clientes y artículos.

## 🔐 Seguridad y Autenticación

* **Encriptación en BD:** Las contraseñas se registran e interactúan en la base de datos aplicando la función nativa `MD5(?)` de MySQL.
* **Consultas Preparadas:** Interacción mediante PDO con *prepared statements* para prevenir ataques de Inyección SQL.
* **Protección de Rutas:** Restricción de acceso a las secciones de Carrito, Compras y Pedidos a usuarios no autenticados en sesión.

## ⚙️ Funcionalidades Implementadas

### 1. Autenticación de Usuarios

* Formulario de Registro con verificación de email único.
* Login con validación `MD5()` en consulta SQL.
* Gestión de sesión activa (`$_SESSION['usuario']`) y Logout.

### 2. Catálogo e Inventario en Tiempo Real

* Despliegue dinámico de productos con stock, descripción y precio.
* **Control de Stock Restante Visible:** El catálogo resta del stock mostrado las unidades que el usuario ya tiene guardadas en su carrito activo, impidiendo añadir más unidades de las disponibles.

### 3. Cesta de la Compra Interactiva

* Añadido acumulativo de artículos a la cesta.
* Ajuste de unidades en tiempo real mediante botones de incremento (`+`) y decremento (`-`).
* Eliminación individual o vaciado completo de la cesta.

### 4. Checkout y Cancelación de Pedidos

* **Finalización de Compra:** Convierte la cesta en registros en la tabla `PEDIDO`, descuenta el stock de la BD y vacía el carrito.
* **Historial de Pedidos:** Tabla resumen con totales, fechas y artículos comprados.
* **Cancelación de Pedidos:** Permite revocar una compra en el historial, eliminando el registro y restituyendo automáticamente el stock al catálogo.

## 🚀 Instalación y Despliegue

1. **Clonar el repositorio:**

   ```bash
   git clone https://github.com/adriitr16/PROYECTO_TIENDA_DWES.git
   cd PROYECTO_TIENDA_DWES
   ```

2. **Crear e importar la base de datos (vía MySQL 8.0 Command Line Client):**

   Abre la consola de MySQL interactiva e ingresa con tu usuario administrador:

   ```sql
   mysql -u root -p
   ```

   Crea la base de datos e importa las tablas:

   ```sql
   CREATE DATABASE tienda_dwes;
   USE tienda_dwes;
   -- Ejecutar el script DDL con las tablas e inserciones iniciales
   SOURCE ruta/hacia/tu/script.sql;
   ```

3. **Configurar el archivo de entorno (`.env`):**

   Crea un archivo `.env` en la raíz del proyecto con las credenciales de tu servidor de MySQL:

   ```ini
   DB_HOST=localhost
   DB_NAME=tienda_dwes
   DB_USER=root
   DB_PASS=tu_contraseña
   ```

4. **Desplegar con el Servidor Integrado de PHP:**

   Ejecuta el siguiente comando desde la raíz del proyecto para iniciar el servidor de desarrollo:

   ```bash
   php -S localhost:8000
   ```

5. **Acceder a la aplicación:**

   Abre tu navegador web e ingresa a: `http://localhost:8000`