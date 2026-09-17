<?php

namespace App\Controllers;

use App\Models\PizzaModel;
use App\Models\OrderModel;

/**
 * OrderController - Controlador para la gestión de pedidos en local/mesa e Historial
 */
class OrderController {
    private PizzaModel $pizzaModel;
    private OrderModel $orderModel;

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->pizzaModel = new PizzaModel();
        $this->orderModel = new OrderModel();
    }

    /**
     * Muestra el formulario POST de realización de pedido para mesa
     */
    public function create(): void {
        $pizzas = $this->pizzaModel->getAll();
        $selectedPizzaId = (int)($_GET['pizza_id'] ?? 1);
        $pizzaSeleccionada = $this->pizzaModel->getById($selectedPizzaId) ?? $pizzas[0];

        $pedidoTemporal = $this->orderModel->obtenerUltimoPedido();

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/order/form.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Procesa el envío del formulario mediante el método POST para el local
     */
    public function store(): void {
        // Verificar que la petición sea de tipo POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=order');
            exit;
        }

        // Validar campos recibidos en $_POST
        $errores = $this->orderModel->validate($_POST);

        $selectedPizzaId = (int)($_POST['pizza_id'] ?? 0);
        $pizzaBase = $this->pizzaModel->getById($selectedPizzaId);

        if (!$pizzaBase) {
            $errores[] = 'La pizza seleccionada no existe en el menú virtual.';
        }

        if (!empty($errores)) {
            $pizzas = $this->pizzaModel->getAll();
            $pizzaSeleccionada = $pizzaBase ?? $pizzas[0];
            $pedidoTemporal = $this->orderModel->obtenerUltimoPedido();

            require_once __DIR__ . '/../Views/layouts/header.php';
            require_once __DIR__ . '/../Views/order/form.php';
            require_once __DIR__ . '/../Views/layouts/footer.php';
            return;
        }

        // Procesar pedido en local y calcular totales
        $ordenProcesada = $this->orderModel->createOrder($_POST, $pizzaBase);

        // Almacenar en el Historial acumulativo de la Sesión de PHP
        $this->orderModel->guardarEnHistorialSesion($ordenProcesada);

        // Capturar la información técnica recibida por $_POST para demostración académica
        $postPayloadEducativo = $_POST;

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/order/confirmation.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Muestra la vista de Historial de Pedidos realizados en la sesión actual
     */
    public function history(): void {
        $historial = $this->orderModel->obtenerHistorial();

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/order/history.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }
}
