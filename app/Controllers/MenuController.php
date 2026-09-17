<?php

namespace App\Controllers;

use App\Models\PizzaModel;

/**
 * MenuController - Controlador para la gestión del catálogo de pizzas (GET)
 */
class MenuController {
    private PizzaModel $pizzaModel;

    public function __construct() {
        $this->pizzaModel = new PizzaModel();
    }

    /**
     * Muestra la página principal con el catálogo completo y el formulario GET de filtro
     */
    public function index(): void {
        $pizzas = $this->pizzaModel->getAll();
        $categorias = $this->pizzaModel->getCategories();

        // Parámetros por defecto para el formulario GET
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $maxPrice = (float)($_GET['max_price'] ?? 0);

        // Si se han enviado parámetros por GET, filtrar
        $esBusquedaGET = isset($_GET['filtrar']) || !empty($search) || !empty($category) || $maxPrice > 0;
        
        if ($esBusquedaGET) {
            $pizzas = $this->pizzaModel->filterPizzas($search, $category, $maxPrice);
        }

        // Cargar vistas
        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/menu/index.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Vista dedicada de respuesta para peticiones GET (Demostración de Query String y $_GET)
     */
    public function searchResults(): void {
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $maxPrice = (float)($_GET['max_price'] ?? 0);

        $pizzasFiltradas = $this->pizzaModel->filterPizzas($search, $category, $maxPrice);
        $categorias = $this->pizzaModel->getCategories();

        // Capturar Query String original de la URL
        $queryString = $_SERVER['QUERY_STRING'] ?? '';

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/menu/search_results.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Muestra el formulario para registrar una nueva pizza con variedad e imagen
     */
    public function addPizza(): void {
        $categorias = $this->pizzaModel->getCategories();
        $formData = [];
        $errores = [];
        $mensajeExito = '';

        if (isset($_GET['created']) && $_GET['created'] == '1') {
            $mensajeExito = '¡La nueva pizza ha sido agregada exitosamente al Menú Virtual!';
        }

        require_once __DIR__ . '/../Views/layouts/header.php';
        require_once __DIR__ . '/../Views/menu/add_pizza.php';
        require_once __DIR__ . '/../Views/layouts/footer.php';
    }

    /**
     * Procesa la solicitud POST para guardar una nueva pizza en el menú
     */
    public function storePizza(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=add_pizza');
            exit;
        }

        $result = $this->pizzaModel->savePizza($_POST, $_FILES);
        $categorias = $this->pizzaModel->getCategories();

        if ($result['success']) {
            $nuevaPizza = $result['pizza'];
            $mensajeExito = "¡Excelente! La pizza '{$nuevaPizza['nombre']}' ha sido creada y guardada con éxito en el catálogo.";
            $formData = []; // Limpiar formulario
            $errores = [];

            require_once __DIR__ . '/../Views/layouts/header.php';
            require_once __DIR__ . '/../Views/menu/add_pizza.php';
            require_once __DIR__ . '/../Views/layouts/footer.php';
        } else {
            $errores = $result['errors'];
            $formData = $_POST;
            $mensajeExito = '';

            require_once __DIR__ . '/../Views/layouts/header.php';
            require_once __DIR__ . '/../Views/menu/add_pizza.php';
            require_once __DIR__ . '/../Views/layouts/footer.php';
        }
    }
}

