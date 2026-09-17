<div class="container my-5">

    <!-- Encabezado de Respuesta GET -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="badge bg-info text-dark font-monospace mb-2 fs-6">
                <i class="fa-solid fa-server me-1"></i> Respuesta de Servidor
            </span>
            <h2 class="brand-font fw-bold mb-0">Resultados de la Petición</h2>
        </div>
        <a href="index.php?action=home" class="btn btn-outline-danger">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al Menú Principal
        </a>
    </div>

    <!-- PANEL DEMOSTRATIVO DE ANATOMÍA HTTP GET -->
    <div class="row g-4 mb-5">
        
        <!-- Tarjeta 1: URL y Query String -->
        <div class="col-md-6">
            <div class="card h-100 border-info shadow-sm bg-dark text-white">
                <div class="card-header bg-info text-dark fw-bold">
                    <i class="fa-solid fa-link me-2"></i>1. URL y Query String Generado
                </div>
                <div class="card-body">
                    <p class="small text-white-50">
                        Los formularios enviados mediante el método <strong>GET</strong> codifican los pares clave-valor en la propia dirección URL como una cadena de consulta (Query String) delimitada por <code>?</code> y <code>&</code>.
                    </p>
                    <div class="p-3 bg-black rounded border border-secondary font-monospace text-warning text-break small">
                        <strong>URL Actual:</strong><br>
                        http://localhost:8000/index.php?<?= htmlspecialchars($queryString) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tarjeta 2: Array Superglobal $_GET en PHP -->
        <div class="col-md-6">
            <div class="card h-100 border-success shadow-sm bg-dark text-white">
                <div class="card-header bg-success text-white fw-bold">
                    <i class="fa-solid fa-code me-2"></i>2. Servidor PHP: Contenido de $_GET
                </div>
                <div class="card-body">
                    <p class="small text-white-50">
                        El controlador <code>MenuController</code> lee los parámetros directamente del array superglobal <code>$_GET</code> en el servidor:
                    </p>
                    <div class="p-3 bg-black rounded border border-secondary font-monospace text-success small">
                        <pre class="mb-0"><?= htmlspecialchars(print_r($_GET, true)) ?></pre>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Resultados del Filtro GET en HTML -->
    <h3 class="fw-bold mb-3 border-bottom pb-2">
        <i class="fa-solid fa-pizza-slice text-danger me-2"></i>Pizzas Encontradas (<?= count($pizzasFiltradas) ?>)
    </h3>

    <div class="row g-4">
        <?php if (empty($pizzasFiltradas)): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-4">
                    <h5>No coinciden resultados para esta combinación de parámetros GET.</h5>
                    <a href="index.php?action=home" class="btn btn-secondary mt-2">Restablecer Búsqueda</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($pizzasFiltradas as $pizza): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="assets/images/<?= htmlspecialchars($pizza['imagen']) ?>" class="card-img-top pizza-img" alt="<?= htmlspecialchars($pizza['nombre']) ?>">
                        <div class="card-body">
                            <span class="badge bg-danger-subtle text-danger text-uppercase mb-2"><?= htmlspecialchars($pizza['categoria']) ?></span>
                            <h5 class="fw-bold fs-6"><?= htmlspecialchars($pizza['nombre']) ?></h5>
                            <p class="text-muted small mb-2"><?= htmlspecialchars($pizza['descripcion']) ?></p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-5 fw-bold text-danger">$<?= number_format($pizza['precio'], 2) ?></span>
                                <a href="index.php?action=home" class="btn btn-outline-danger btn-sm">
                                    <i class="fa-solid fa-utensils me-1"></i>Ver en el Menú
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
