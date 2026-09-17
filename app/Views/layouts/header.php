<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$cantHistorial = count($_SESSION['historial_pedidos'] ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Pizza Nostra | Menú Virtual para Mesas & Pedidos en Local</title>

    <!-- Meta Tags para SEO -->
    <meta name="description" content="Menú Virtual interactivo de la Pizzería La Pizza Nostra para consumo en local y atención en mesa.">
    <meta name="keywords" content="menu virtual, pizzeria, atencion en mesa, pedidos en local, get, post, mvc, historial">

    <!-- CSS Libraries (Bibliotecas Externas) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Playfair+Display:ital,wght@0,700;1,600&display=swap" rel="stylesheet">

    <!-- CSS Propio (Estilos Personalizados del Menú Virtual) -->
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>

    <!-- Navegación Principal -->
    <nav class="navbar navbar-expand-lg sticky-top custom-navbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
                <span class="logo-icon"><i class="fa-solid fa-pizza-slice text-danger"></i></span>
                <span class="logo-text">La Pizza <strong class="text-warning">Nostra</strong></span>
            </a>

            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fa-solid fa-bars fs-4"></i>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-2">
                    <li class="nav-item">
                        <a class="nav-link <?= (!isset($_GET['action']) || $_GET['action'] == 'home') ? 'active' : '' ?>" href="index.php?action=home">
                            <i class="fa-solid fa-utensils text-warning me-1"></i> Menú Virtual 
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && ($_GET['action'] == 'add_pizza' || $_GET['action'] == 'store_pizza')) ? 'active' : '' ?>" href="index.php?action=add_pizza">
                            <i class="fa-solid fa-plus-circle text-danger me-1"></i> Agregar Pizza
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'order') ? 'active' : '' ?>" href="index.php?action=order">
                            <i class="fa-solid fa-chair text-success me-1"></i> Pedido para Mesa
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= (isset($_GET['action']) && $_GET['action'] == 'history') ? 'active' : '' ?>" href="index.php?action=history">
                            <i class="fa-solid fa-clock-rotate-left text-info me-1"></i> Mis Pedidos
                            <?php if ($cantHistorial > 0): ?>
                                <span class="badge bg-danger rounded-pill ms-1"><?= $cantHistorial ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <span class="badge bg-success rounded-pill px-3 py-2 border border-warning">
                            <i class="fa-solid fa-shop me-1"></i> Atención en Local
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
