<?php
session_start();

// Cargar DB y Repositorios
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../repositories/ClienteRepository.php';
require_once __DIR__ . '/../repositories/ProductoRepository.php';
require_once __DIR__ . '/../repositories/CarritoRepository.php';
require_once __DIR__ . '/../repositories/PedidoRepository.php';

// Instancias
$clienteRepo  = new ClienteRepository();
$productoRepo = new ProductoRepository();
$carritoRepo  = new CarritoRepository();
$pedidoRepo   = new PedidoRepository();

$action = $_GET['action'] ?? 'catalogo';
$error = null;

switch ($action) {

    // --- REGISTRO ---
    case 'do_registro':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre   = trim($_POST['nombre'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $pass     = $_POST['password'] ?? '';
            $telefono = trim($_POST['telefono'] ?? '');

            if (empty($nombre) || empty($email) || empty($pass)) {
                $error = "Por favor, completa los campos obligatorios.";
                $vistaContenido = 'registro';
            } elseif ($clienteRepo->obtenerPorEmail($email)) {
                $error = "El correo electrónico ya está registrado.";
                $vistaContenido = 'registro';
            } else {
                $clienteRepo->crearCliente($nombre, $email, $pass, $telefono);
                header('Location: index.php?action=login');
                exit;
            }
        }
        break;

    // --- LOGIN ---
    case 'do_login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $pass  = $_POST['password'] ?? '';

            $usuario = $clienteRepo->autenticar($email, $pass);

            if ($usuario) {
                $_SESSION['usuario'] = $usuario;
                header('Location: index.php');
                exit;
            } else {
                $error = "Credenciales incorrectas.";
                $vistaContenido = 'login';
            }
        }
        break;

    // --- LOGOUT ---
    case 'logout':
        session_destroy();
        header('Location: index.php');
        exit;

    // --- AÑADIR AL CARRITO (CON CONTROL DE STOCK ACUMULADO) ---
    case 'add_cart':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idProducto     = (int)($_POST['id_producto'] ?? 0);
            $cantidadAñadir = (int)($_POST['cantidad'] ?? 1);
            $idCliente      = $_SESSION['usuario']['ID_CLIENTE'];

            // 1. Averiguar cuántas unidades de este producto ya tiene en el carrito
            $itemsCarrito = $carritoRepo->obtenerCarritoPorCliente($idCliente);
            $cantidadEnCarrito = 0;

            foreach ($itemsCarrito as $item) {
                if ((int)$item['ID_PRODUCTO'] === $idProducto) {
                    $cantidadEnCarrito = (int)$item['CANTIDAD'];
                    break;
                }
            }

            $cantidadTotalSolicitada = $cantidadEnCarrito + $cantidadAñadir;

            // 2. Validar contra el stock real de la base de datos
            if ($productoRepo->comprobarStock($idProducto, $cantidadTotalSolicitada)) {
                $carritoRepo->agregarProducto($idCliente, $idProducto, $cantidadAñadir);
            } else {
                $_SESSION['error_stock'] = "No puedes añadir esa cantidad. Supera el stock disponible en la tienda.";
            }
        }
        header('Location: index.php?action=carrito');
        exit;

    // --- ELIMINAR DEL CARRITO ---
    case 'remove_cart':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $idCarrito = (int)($_GET['id_carrito'] ?? 0);
        if ($idCarrito > 0) {
            $carritoRepo->eliminarProducto($idCarrito);
        }
        header('Location: index.php?action=carrito');
        exit;

    // --- FINALIZAR COMPRA (PROCESAR PEDIDO Y DESCONTAR STOCK) ---
    case 'checkout':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $idCliente = $_SESSION['usuario']['ID_CLIENTE'];
        $items = $carritoRepo->obtenerCarritoPorCliente($idCliente);

        if (empty($items)) {
            header('Location: index.php?action=carrito');
            exit;
        }

        // Comprobar que todos los ítems tengan stock antes de proceder
        foreach ($items as $item) {
            if (!$productoRepo->comprobarStock($item['ID_PRODUCTO'], $item['CANTIDAD'])) {
                $_SESSION['error_stock'] = "El producto " . htmlspecialchars($item['NOMBRE']) . " excede el stock disponible.";
                header('Location: index.php?action=carrito');
                exit;
            }
        }

        // Insertar filas en PEDIDO y descontar del STOCK
        foreach ($items as $item) {
            $pedidoRepo->crearPedido($idCliente, $item['ID_PRODUCTO'], $item['CANTIDAD']);
            $productoRepo->descontarStock($item['ID_PRODUCTO'], $item['CANTIDAD']);
        }

        // Vaciar la cesta solo tras registrar con éxito los pedidos
        $carritoRepo->vaciarCarrito($idCliente);

        header('Location: index.php?action=mis_pedidos');
        exit;

    // --- CARGA DE VISTAS ---
    case 'login':
        $vistaContenido = 'login';
        break;

    case 'registro':
        $vistaContenido = 'registro';
        break;

    case 'carrito':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $vistaContenido = 'carrito';
        if (isset($_SESSION['error_stock'])) {
            $error = $_SESSION['error_stock'];
            unset($_SESSION['error_stock']);
        }
        $itemsCarrito = $carritoRepo->obtenerCarritoPorCliente($_SESSION['usuario']['ID_CLIENTE']);
        break;

    case 'mis_pedidos':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $vistaContenido = 'mis_pedidos';
        $pedidos = $pedidoRepo->obtenerPedidosPorCliente($_SESSION['usuario']['ID_CLIENTE']);
        break;
    
    // CANCELAR PEDIDO
    case 'cancel_order':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $idPedido  = (int)($_GET['id_pedido'] ?? 0);
        $idCliente = $_SESSION['usuario']['ID_CLIENTE'];

        if ($idPedido > 0) {
            // Cancelar pedido y obtener datos
            $pedidoCancelado = $pedidoRepo->cancelarPedido($idPedido, $idCliente);

            if ($pedidoCancelado) {
                // Devolver el stock a la base de datos
                $productoRepo->descontarStock($pedidoCancelado['ID_PRODUCTO'], -$pedidoCancelado['CANTIDAD']);
            }
        }

        header('Location: index.php?action=mis_pedidos');
        exit;
    
    // --- ACTUALIZAR CANTIDAD EN EL CARRITO (+ / -) ---
    case 'update_cart':
        if (!isset($_SESSION['usuario'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $idCarrito      = (int)($_GET['id_carrito'] ?? 0);
        $nuevaCantidad = (int)($_GET['cantidad'] ?? 0);
        $idProducto    = (int)($_GET['id_producto'] ?? 0);

        if ($idCarrito > 0) {
            if ($nuevaCantidad <= 0) {
                // Si llega a 0 o menos, eliminar del carrito
                $carritoRepo->eliminarProducto($idCarrito);
            } else {
                // Validar si la nueva cantidad está dentro del stock de la BD
                if ($productoRepo->comprobarStock($idProducto, $nuevaCantidad)) {
                    $carritoRepo->actualizarCantidad($idCarrito, $nuevaCantidad);
                } else {
                    $_SESSION['error_stock'] = "No puedes aumentar la cantidad. Excede el stock disponible.";
                }
            }
        }
        header('Location: index.php?action=carrito');
        exit;

    case 'catalogo':
    default:
        $vistaContenido = 'catalogo';
        $productos = $productoRepo->obtenerTodos();

        // Si el usuario ha iniciado sesión, ajustar el stock visible descontando lo que ya tiene en la cesta
        if (isset($_SESSION['usuario'])) {
            $idCliente = $_SESSION['usuario']['ID_CLIENTE'];
            $itemsCarrito = $carritoRepo->obtenerCarritoPorCliente($idCliente);

            // Mapear cantidades en carrito por ID_PRODUCTO
            $cantidadesEnCarrito = [];
            foreach ($itemsCarrito as $item) {
                $cantidadesEnCarrito[$item['ID_PRODUCTO']] = (int)$item['CANTIDAD'];
            }

            // Restar al stock mostrado la cantidad que ya está en el carrito
            foreach ($productos as &$producto) {
                $idProd = $producto['ID_PRODUCTO'];
                if (isset($cantidadesEnCarrito[$idProd])) {
                    $stockRestante = $producto['STOCK'] - $cantidadesEnCarrito[$idProd];
                    $producto['STOCK'] = max(0, $stockRestante); // Evitar números negativos
                }
            }
            unset($producto); // Romper referencia del foreach
        }
        break;
    }

// Cargar la vista única principal
require_once __DIR__ . '/../views/mainView.phtml';