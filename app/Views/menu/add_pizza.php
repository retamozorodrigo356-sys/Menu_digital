<!-- Hero Banner de Alta de Producto -->
<section class="hero-section mb-4 position-relative overflow-hidden" style="min-height: 240px;">
    <div class="hero-overlay"></div>
    <img src="assets/images/pizzeria_hero.jpg" alt="La Pizza Nostra Admin" class="hero-bg-img">
    <div class="container position-relative py-4 text-center text-white">
        <span class="badge bg-warning text-dark font-monospace mb-2 fs-6 px-3 py-2 rounded-pill">
            <i class="fa-solid fa-plus-circle me-1"></i> Administración del Catálogo
        </span>
        <h1 class="display-4 brand-font fw-bold text-shadow mb-1">Agregar Nueva Pizza & Variedad</h1>
        <p class="lead max-w-600 mx-auto text-light opacity-90 fs-6">
            Registra una nueva especialidad para el menú virtual de La Pizza Nostra con su respectiva imagen, precio e ingredientes.
        </p>
    </div>
</section>

<div class="container my-4">

    <!-- Enlace de regreso -->
    <div class="mb-3">
        <a href="index.php?action=home" class="btn btn-outline-secondary btn-sm fw-bold">
            <i class="fa-solid fa-arrow-left me-1"></i> Volver al Menú Virtual
        </a>
    </div>

    <!-- Alertas de Mensajes -->
    <?php if (!empty($mensajeExito)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-circle-check fs-2 text-success me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1 fw-bold">¡Registro Exitoso!</h5>
                    <p class="mb-0"><?= htmlspecialchars($mensajeExito) ?></p>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <div class="mt-3">
                <a href="index.php?action=home" class="btn btn-success btn-sm fw-bold me-2">
                    <i class="fa-solid fa-utensils me-1"></i> Ver en el Menú Virtual
                </a>
                <a href="index.php?action=add_pizza" class="btn btn-outline-success btn-sm fw-bold">
                    <i class="fa-solid fa-plus me-1"></i> Agregar Otra Pizza
                </a>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert">
            <div class="d-flex align-items-start">
                <i class="fa-solid fa-circle-exclamation fs-3 text-danger me-3 mt-1"></i>
                <div>
                    <h5 class="alert-heading mb-1 fw-bold">Por favor corrige los siguientes errores:</h5>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errores as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <!-- Columna Izquierda: Formulario de Registro -->
        <div class="col-lg-7">
            <div class="card form-card shadow-lg border-0">
                <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fs-5">
                        <i class="fa-solid fa-pizza-slice me-2"></i> Formulario de Registro de Pizza
                    </h4>
                    <span class="badge bg-warning text-dark font-monospace">Registro de Nuevas Pizzas</span>
                </div>
                <div class="card-body p-4">
                    
                    <form action="index.php?action=store_pizza" method="POST" enctype="multipart/form-data" id="form-add-pizza">
                        
                        <!-- SECCIÓN 1: DATOS BÁSICOS -->
                        <fieldset class="border p-3 rounded-3 mb-4 bg-light">
                            <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                                <i class="fa-solid fa-circle-info me-1"></i> Información General
                            </legend>

                            <div class="mb-3">
                                <label for="nombre" class="form-label fw-bold">
                                    <i class="fa-solid fa-font me-1"></i> Nombre de la Pizza: <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg" 
                                       id="nombre" 
                                       name="nombre" 
                                       required
                                       value="<?= htmlspecialchars($formData['nombre'] ?? '') ?>" 
                                       placeholder="Nombre de la Pizza...">
                            </div>

                            <div class="row g-3">
                                <!-- Categoría / Variedad -->
                                <div class="col-md-6">
                                    <label for="categoria" class="form-label fw-bold">
                                        <i class="fa-solid fa-layer-group me-1"></i> Variedad / Categoría: <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select" id="categoria" name="categoria" onchange="toggleNuevaCategoria(this.value)">
                                        <option value="">Selecciona Variedad</option>
                                        <?php foreach ($categorias as $key => $nombreCat): ?>
                                            <option value="<?= htmlspecialchars($key) ?>" <?= (isset($formData['categoria']) && $formData['categoria'] === $key) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($nombreCat) ?>
                                            </option>
                                        <?php endforeach; ?>
                                        <option value="__NUEVA__" <?= (isset($formData['nueva_categoria']) && !empty($formData['nueva_categoria'])) ? 'selected' : '' ?>>
                                            ✨ + Crear Nueva Variedad...
                                        </option>
                                    </select>
                                </div>

                                <!-- Input dinámico para Nueva Categoría -->
                                <div class="col-md-6 <?= (isset($formData['nueva_categoria']) && !empty($formData['nueva_categoria'])) ? '' : 'd-none' ?>" id="container-nueva-categoria">
                                    <label for="nueva_categoria" class="form-label fw-bold text-success">
                                        <i class="fa-solid fa-sparkles me-1"></i> Nombre de Nueva Variedad:
                                    </label>
                                    <input type="text" 
                                           class="form-control border-success" 
                                           id="nueva_categoria" 
                                           name="nueva_categoria" 
                                           value="<?= htmlspecialchars($formData['nueva_categoria'] ?? '') ?>" 
                                           placeholder="Nombre de la Nueva Variedad">
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <!-- Precio -->
                                <div class="col-md-6">
                                    <label for="precio" class="form-label fw-bold">
                                        <i class="fa-solid fa-tag me-1"></i> Precio ($): <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text fw-bold text-danger">$</span>
                                        <input type="number" 
                                               step="0.50" 
                                               min="1.00" 
                                               max="500.00" 
                                               class="form-control" 
                                               id="precio" 
                                               name="precio" 
                                               required
                                               value="<?= htmlspecialchars($formData['precio'] ?? '15.00') ?>" 
                                               placeholder="15.00">
                                    </div>
                                </div>

                                <!-- Calorías -->
                                <div class="col-md-6">
                                    <label for="calorias" class="form-label fw-bold">
                                        <i class="fa-solid fa-fire me-1"></i> Calorías Estimadas (kcal):
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               step="10" 
                                               min="100" 
                                               max="3000" 
                                               class="form-control" 
                                               id="calorias" 
                                               name="calorias" 
                                               value="<?= htmlspecialchars($formData['calorias'] ?? '850') ?>" 
                                               placeholder="850">
                                        <span class="input-group-text">kcal</span>
                                    </div>
                                </div>
                            </div>

                        </fieldset>

                        <!-- SECCIÓN 2: DETALLES E INGREDIENTES -->
                        <fieldset class="border p-3 rounded-3 mb-4 bg-light">
                            <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                                <i class="fa-solid fa-list-check me-1"></i> Descripción e Ingredientes
                            </legend>

                            <div class="mb-3">
                                <label for="descripcion" class="form-label fw-bold">
                                    <i class="fa-solid fa-align-left me-1"></i> Descripción Detallada: <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="descripcion" 
                                          name="descripcion" 
                                          rows="3" 
                                          required
                                          placeholder="Describe los sabores, la masa artesanal o la preparación especial..."><?= htmlspecialchars($formData['descripcion'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="ingredientes" class="form-label fw-bold">
                                    <i class="fa-solid fa-pepper-hot me-1"></i> Lista de Ingredientes (Separados por comas): <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="ingredientes" 
                                       name="ingredientes" 
                                       required
                                       value="<?= htmlspecialchars($formData['ingredientes'] ?? '') ?>" 
                                       placeholder="Ej: Salsa Napolitana, Mozzarella, Peperoni, Jamón, Alcaparras">
                                <div class="form-text text-muted">Ingresa cada ingrediente separado por una coma. Se mostrarán como etiquetas.</div>
                            </div>

                        </fieldset>

                        <!-- SECCIÓN 3: SUBIDA DE IMAGEN & OPCIONES -->
                        <fieldset class="border p-3 rounded-3 mb-4 bg-light">
                            <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                                <i class="fa-solid fa-image me-1"></i> Imagen de la Pizza & Destacado
                            </legend>

                            <div class="mb-3">
                                <label for="imagen" class="form-label fw-bold">
                                    <i class="fa-solid fa-upload me-1"></i> Subir Imagen desde tu Equipo:
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="imagen" 
                                       name="imagen" 
                                       accept="image/png, image/jpeg, image/jpg, image/webp, image/gif" 
                                       onchange="previewUploadedImage(this)">
                                <div class="form-text text-muted">Formatos aceptados: JPG, PNG, WEBP, GIF. Tamaño recomendado: 800x600 px.</div>
                            </div>

          

                            <!-- Destacada / Popular -->
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="popular" name="popular" value="1" <?= (isset($formData['popular']) && $formData['popular']) ? 'checked' : '' ?> onchange="updatePopularBadge(this.checked)">
                                <label class="form-check-label fw-bold text-dark" for="popular">
                                    <i class="fa-solid fa-star text-warning me-1"></i> Destacar como "Pizza Favorita de la Casa"
                                </label>
                            </div>

                        </fieldset>

                        <!-- BOTONES DE ACCIÓN -->
                        <div class="d-flex justify-content-between align-items-center pt-2">
                            <a href="index.php?action=home" class="btn btn-outline-secondary btn-lg">
                                Cancelar
                            </a>
                            <button type="submit" class="btn btn-danger btn-lg px-4 fw-bold shadow">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Guardar y Publicar Pizza
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <!-- Columna Derecha: Previsualización en Tiempo Real -->
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 90px; z-index: 10;">
                <div class="card border-0 shadow-lg mb-3">
                    <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                        <span class="fw-bold"><i class="fa-solid fa-eye text-warning me-2"></i>Vista Previa en Tiempo Real</span>
                        <span class="badge bg-danger">En Vivo</span>
                    </div>
                    <div class="card-body p-3 bg-light">
                        <p class="text-muted small mb-3">Así se visualizará la nueva pizza en la grilla del menú virtual para las mesas:</p>

                        <!-- Tarjeta de Producto Simulada -->
                        <div class="card h-100 product-card shadow-sm border-0 position-relative overflow-hidden bg-white">
                            
                            <span id="preview-badge-popular" class="position-absolute top-0 end-0 bg-warning text-dark px-3 py-1 fw-bold rounded-start-pill text-uppercase fs-7 m-2 shadow-sm d-none">
                                <i class="fa-solid fa-star me-1"></i> Favorita
                            </span>

                            <div class="img-container position-relative">
                                <img id="preview-img" src="assets/images/margherita.jpg" 
                                     class="card-img-top pizza-img" 
                                     alt="Vista previa de pizza"
                                     style="height: 200px; object-fit: cover;">
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span id="preview-categoria" class="badge bg-danger-subtle text-danger font-monospace text-uppercase">
                                        Artesanal
                                    </span>
                                    <span class="text-muted small">
                                        <i class="fa-solid fa-fire text-danger me-1"></i><span id="preview-calorias">850</span> kcal
                                    </span>
                                </div>

                                <h5 id="preview-nombre" class="card-title fw-bold text-dark mb-2">
                                    Nombre de la Pizza
                                </h5>
                                <p id="preview-descripcion" class="card-text text-muted small flex-grow-1">
                                    La descripción detallada de la pizza se mostrará en este apartado...
                                </p>

                                <div class="mb-3">
                                    <small class="fw-bold d-block text-secondary mb-1">Ingredientes:</small>
                                    <div id="preview-ingredientes" class="d-flex flex-wrap gap-1">
                                        <span class="badge bg-light text-dark border font-weight-normal">Tomate</span>
                                        <span class="badge bg-light text-dark border font-weight-normal">Mozzarella</span>
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-auto d-flex justify-content-between align-items-center">
                                    <div>
                                        <small class="text-muted d-block">Precio:</small>
                                        <span id="preview-precio" class="fs-4 fw-bold text-danger">$15.00</span>
                                    </div>
                                    <button class="btn btn-warning btn-sm fw-bold" disabled>
                                        <i class="fa-solid fa-utensils me-1"></i> Pedir a Mesa
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>

</div>

<!-- SCRIPT DE INTERACTIVIDAD DE LA VISTA PREVIA Y CAMPOS DINÁMICOS -->
<script>
function toggleNuevaCategoria(val) {
    const container = document.getElementById('container-nueva-categoria');
    const inputNueva = document.getElementById('nueva_categoria');
    if (val === '__NUEVA__') {
        container.classList.remove('d-none');
        inputNueva.required = true;
        inputNueva.focus();
        updatePreviewCategory(inputNueva.value || 'NUEVA VARIEDAD');
    } else {
        container.classList.add('d-none');
        inputNueva.required = false;
        const select = document.getElementById('categoria');
        const selectedText = select.options[select.selectedIndex]?.text || 'VARIEDAD';
        updatePreviewCategory(selectedText);
    }
}

function updatePreviewCategory(catText) {
    document.getElementById('preview-categoria').textContent = catText.toUpperCase();
}

function previewUploadedImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function setPresetImage(imageName) {
    const fileInput = document.getElementById('imagen');
    fileInput.value = ''; // Limpiar el archivo subido si elige un preset
    document.getElementById('preview-img').src = 'assets/images/' + imageName;
}

function updatePopularBadge(isPopular) {
    const badge = document.getElementById('preview-badge-popular');
    if (isPopular) {
        badge.classList.remove('d-none');
    } else {
        badge.classList.add('d-none');
    }
}

// Event Listeners para actualizar la tarjeta en tiempo real
document.addEventListener('DOMContentLoaded', function() {
    const nombreInput = document.getElementById('nombre');
    const precioInput = document.getElementById('precio');
    const caloriasInput = document.getElementById('calorias');
    const descripcionInput = document.getElementById('descripcion');
    const ingredientesInput = document.getElementById('ingredientes');
    const nuevaCatInput = document.getElementById('nueva_categoria');

    nombreInput.addEventListener('input', function() {
        document.getElementById('preview-nombre').textContent = this.value.trim() || 'Nombre de la Pizza';
    });

    precioInput.addEventListener('input', function() {
        const val = parseFloat(this.value) || 0;
        document.getElementById('preview-precio').textContent = '$' + val.toFixed(2);
    });

    caloriasInput.addEventListener('input', function() {
        document.getElementById('preview-calorias').textContent = this.value || '850';
    });

    descripcionInput.addEventListener('input', function() {
        document.getElementById('preview-descripcion').textContent = this.value.trim() || 'La descripción detallada de la pizza se mostrará en este apartado...';
    });

    ingredientesInput.addEventListener('input', function() {
        const container = document.getElementById('preview-ingredientes');
        container.innerHTML = '';
        const items = this.value.split(',').map(i => i.trim()).filter(i => i.length > 0);
        
        if (items.length === 0) {
            container.innerHTML = '<span class="badge bg-light text-dark border font-weight-normal">Ingrediente 1</span>';
        } else {
            items.forEach(ing => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-light text-dark border font-weight-normal';
                badge.textContent = ing;
                container.appendChild(badge);
            });
        }
    });

    nuevaCatInput.addEventListener('input', function() {
        if (document.getElementById('categoria').value === '__NUEVA__') {
            updatePreviewCategory(this.value.trim() || 'NUEVA VARIEDAD');
        }
    });
});
</script>
