<!-- Hero Section de la Pizzería - Menú Virtual -->
<section class="hero-section mb-4 position-relative overflow-hidden">
    <div class="hero-overlay"></div>
    <img src="assets/images/pizzeria_hero.jpg" alt="Menú Virtual de La Pizza Nostra" class="hero-bg-img">
    <div class="container position-relative py-5 text-center text-white">
        <span class="badge bg-warning text-dark font-monospace mb-3 fs-6 px-3 py-2 rounded-pill">
            <i class="fa-solid fa-utensils me-2"></i>Menú Digital de Pizzas Artesanales
        </span>
        <h1 class="display-3 brand-font fw-bold text-shadow mb-2">Menú Digital "La Pizza Nostra"</h1>
        <p class="lead max-w-600 mx-auto text-light opacity-90">
            Explora nuestras pizzas artesanales, busca tu favorita por su nombre y descubre todas las variedades de la casa.
        </p>
    </div>
</section>

<div class="container my-4">

   

   

    <!-- ANATOMÍA DEL FORMULARIO - BÚSQUEDA GET POR NOMBRE DE PIZZA -->
    <div class="card form-card shadow-lg mb-5 border-0">
        <div class="card-header bg-danger text-white py-3">
            <h4 class="mb-0 fs-5"><i class="fa-solid fa-magnifying-glass me-2"></i>Buscar Pizza por Nombre</h4>
        </div>
        <div class="card-body p-4">
            
            <form action="index.php" method="GET" class="needs-validation" id="form-filtro-get">
                <!-- Parámetro oculto para el enrutador MVC -->
                <input type="hidden" name="action" value="search_results">

                <!-- AGRUPACIÓN Y ESTRUCTURA: FIELDSET & LEGEND -->
                <fieldset class="border p-3 rounded-3 mb-3 bg-light">
                    <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                        <i class="fa-solid fa-pizza-slice me-1"></i> Búsqueda en el Menú Virtual
                    </legend>

                    <div class="row g-3 align-items-end">
                        
                        <!-- ASOCIACIÓN: LABEL (for="search") CON INPUT (id="search") -->
                        <div class="col-md-6">
                            <label for="search" class="form-label fw-bold">
                                <i class="fa-solid fa-font me-1"></i> Nombre de la Pizza:
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg" 
                                   id="search" 
                                   name="search" 
                                   value="<?= htmlspecialchars($search) ?>" 
                                   placeholder="Busca tu pizza">
                        </div>

                        <!-- ASOCIACIÓN: LABEL (for="category") CON SELECT (id="category") -->
                        <div class="col-md-3">
                            <label for="category" class="form-label fw-bold">
                                <i class="fa-solid fa-list-ul me-1"></i> Categoría:
                            </label>
                            <select class="form-select form-select-lg" id="category" name="category">
                                <option value="">Todas</option>
                                <?php foreach ($categorias as $key => $nombreCat): ?>
                                    <option value="<?= $key ?>" <?= ($category === $key) ? 'selected' : '' ?>>
                                        <?= $nombreCat ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Botones de Acción del Formulario GET -->
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" name="filtrar" value="1" class="btn btn-danger btn-lg w-100 fw-bold">
                                <i class="fa-solid fa-magnifying-glass me-1"></i> Buscar
                            </button>
                        </div>

                    </div>
                </fieldset>

            </form>

        </div>
    </div>

    <!-- Título de Sección del Menú Virtual -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="brand-font fw-bold mb-1">Pizzas Disponibles en Salón</h2>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-secondary fs-6 px-3 py-2"><?= count($pizzas) ?> Variedades en Carta</span>
            <a href="index.php?action=add_pizza" class="btn btn-danger btn-sm fw-bold shadow-sm px-3 py-2">
                <i class="fa-solid fa-plus-circle me-1"></i> Nueva Pizza
            </a>
        </div>
    </div>

    <!-- Grilla de Productos (Imágenes Referenciadas por Nombre) -->
    <div class="row g-4">
        <?php if (empty($pizzas)): ?>
            <div class="col-12">
                <div class="alert alert-warning text-center py-5 shadow-sm">
                    <i class="fa-solid fa-pizza-slice fs-1 mb-3 text-warning"></i>
                    <h4>No encontramos ninguna pizza con ese nombre</h4>
                    <p class="mb-3">Intenta buscar por "Pepperoni", "Margherita", "Gourmet" o limpia la búsqueda.</p>
                    <a href="index.php?action=home" class="btn btn-danger">Ver todo el Menú Virtual</a>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($pizzas as $pizza): ?>
                <div class="col-md-6 col-lg-3">
                    <div class="card h-100 product-card shadow-sm border-0 position-relative overflow-hidden">
                        
                        <?php if ($pizza['popular']): ?>
                            <span class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 fw-bold rounded-start-pill text-uppercase fs-7 m-2 shadow-sm">
                                <i class="fa-solid fa-star me-1"></i> Favorita
                            </span>
                        <?php endif; ?>

                        <!-- REFERENCIA LOCAL A IMAGEN POR NOMBRE -->
                        <div class="img-container">
                            <img src="assets/images/<?= htmlspecialchars($pizza['imagen']) ?>" 
                                 class="card-img-top pizza-img" 
                                 alt="<?= htmlspecialchars($pizza['nombre']) ?>">
                        </div>

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="badge bg-danger-subtle text-danger font-monospace text-uppercase">
                                    <?= htmlspecialchars($pizza['categoria']) ?>
                                </span>
                                <span class="text-muted small">
                                    <i class="fa-solid fa-fire text-danger me-1"></i><?= $pizza['calorias'] ?> kcal
                                </span>
                            </div>

                            <h5 class="card-title fw-bold text-dark mb-2"><?= htmlspecialchars($pizza['nombre']) ?></h5>
                            <p class="card-text text-muted small flex-grow-1"><?= htmlspecialchars($pizza['descripcion']) ?></p>

                            <!-- Ingredientes etiquetados -->
                            <div class="mb-3">
                                <small class="fw-bold d-block text-secondary mb-1">Ingredientes:</small>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php foreach ($pizza['ingredientes'] as $ing): ?>
                                        <span class="badge bg-light text-dark border font-weight-normal"><?= htmlspecialchars($ing) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                <div>
                                    <small class="text-muted d-block">Precio:</small>
                                    <span class="fs-4 fw-bold text-danger">$<?= number_format($pizza['precio'], 2) ?></span>
                                </div>
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="fa-solid fa-circle-check me-1"></i>Disponible
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

</div>
