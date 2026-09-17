<?php
/**
 * Front Controller - Menú Digital "La Pizza Nostra"
 * Enrutador principal de la arquitectura MVC
 */

// Autoload simple de clases mediante PSR-4 simulado
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

use App\Controllers\MenuController;

// Obtener la acción deseada desde la URL (petición GET)
$action = $_GET['action'] ?? 'home';

// Instanciar controlador
$menuController = new MenuController();

// Enrutamiento de peticiones
switch ($action) {
    case 'home':
        $menuController->index();
        break;

    case 'search_results':
        $menuController->searchResults();
        break;

    case 'add_pizza':
        $menuController->addPizza();
        break;

    case 'store_pizza':
        $menuController->storePizza();
        break;

    default:
        $menuController->index();
        break;
}
