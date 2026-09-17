<div class="container my-5">

    <!-- Encabezado de la Sección POST en Local -->
    <div class="text-center mb-4">
        <span class="badge bg-success text-white font-monospace fs-6 px-3 py-2 rounded-pill mb-2">
            <i class="fa-solid fa-chair me-2"></i>Pedido para Mesa 
        </span>
        <h2 class="display-5 brand-font fw-bold text-dark">Solicitud de Pedido a Cocina</h2>
       
    </div>

    <!-- Alertas de Errores de Validación -->
    <?php if (!empty($errores)): ?>
        <div class="alert alert-danger shadow-sm mb-4">
            <h5 class="alert-heading"><i class="fa-solid fa-triangle-exclamation me-2"></i>Por favor corrige los siguientes errores:</h5>
            <ul class="mb-0">
                <?php foreach ($errores as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        
        <!-- Formulario POST Principal (Columna Izquierda) -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg form-card">
                <div class="card-body p-4 p-md-5">

                    <form action="index.php?action=checkout_submit" method="POST" id="form-pedido-post" class="needs-validation" novalidate>

                        <!-- AGRUPACIÓN 1: DATOS DE LA MESA Y COMENSAL -->
                        <fieldset class="border p-4 rounded-3 mb-4 bg-light">
                            <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                                <i class="fa-solid fa-chair me-2"></i>1. Datos de la Mesa & Comensal
                            </legend>

                            <div class="row g-3">
                                
                                <!-- ASOCIACIÓN: LABEL for="nombre" -> INPUT id="nombre" -->
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label fw-bold">
                                        Nombre del Comensal: <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="nombre" 
                                           name="nombre" 
                                           required 
                                           placeholder="Ej. Carlos Mendoza"
                                           value="<?= htmlspecialchars($_POST['nombre'] ?? ($pedidoTemporal['cliente']['nombre'] ?? '')) ?>">
                                </div>

                                <!-- ASOCIACIÓN: LABEL for="numero_mesa" -> INPUT id="numero_mesa" -->
                                <div class="col-md-6">
                                    <label for="numero_mesa" class="form-label fw-bold">
                                        Número de Mesa / Ubicación: <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control" 
                                           id="numero_mesa" 
                                           name="numero_mesa" 
                                           required 
                                           placeholder="Ej. Mesa 4 / Mesa Terraza / Ventanilla"
                                           value="<?= htmlspecialchars($_POST['numero_mesa'] ?? ($pedidoTemporal['cliente']['numero_mesa'] ?? 'Mesa 1')) ?>">
                                </div>

                                <!-- ASOCIACIÓN: LABEL for="tipo_atencion" -> SELECT id="tipo_atencion" -->
                                <div class="col-md-6">
                                    <label for="tipo_atencion" class="form-label fw-bold">Modalidad de Atención:</label>
                                    <select class="form-select" id="tipo_atencion" name="tipo_atencion">
                                        <option value="en_mesa" selected>🍽️ Consumo en Mesa (Servicio en Salón)</option>
                                        <option value="para_llevar_local">🛍️ Para Llevar (Recoger en Ventanilla del Local)</option>
                                    </select>
                                </div>

                                <!-- ASOCIACIÓN: LABEL for="metodo_pago" -> SELECT id="metodo_pago" -->
                                <div class="col-md-6">
                                    <label for="metodo_pago" class="form-label fw-bold">Forma de Pago en Mesa:</label>
                                    <select class="form-select" id="metodo_pago" name="metodo_pago">
                                        <option value="efectivo">💵 Efectivo en Mesa</option>
                                        <option value="tarjeta">💳 Tarjeta POS / Pagaré</option>
                                        <option value="yape_plin">📱 QR Yape / Plin en Mesa</option>
                                    </select>
                                </div>

                            </div>
                        </fieldset>

                        <!-- AGRUPACIÓN 2: SELECCIÓN Y CONFIGURACIÓN DE PIZZA -->
                        <fieldset class="border p-4 rounded-3 mb-4 bg-light">
                            <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                                <i class="fa-solid fa-pizza-slice me-2"></i>2. Configuración de la Pizza
                            </legend>

                            <div class="row g-3">
                                
                                <!-- ASOCIACIÓN: LABEL for="pizza_id" -> SELECT id="pizza_id" -->
                                <div class="col-12">
                                    <label for="pizza_id" class="form-label fw-bold">Seleccionar Pizza del Menú Virtual:</label>
                                    <select class="form-select form-select-lg border-danger" id="pizza_id" name="pizza_id" onchange="actualizarPrecioRealTime()">
                                        <?php foreach ($pizzas as $p): ?>
                                            <option value="<?= $p['id'] ?>" 
                                                    data-precio="<?= $p['precio'] ?>" 
                                                    <?= ($p['id'] == $pizzaSeleccionada['id']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($p['nombre']) ?> - Base $<?= number_format($p['precio'], 2) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- ASOCIACIÓN: LABEL for="tamano" -> SELECT id="tamano" -->
                                <div class="col-md-4">
                                    <label for="tamano" class="form-label fw-bold">Tamaño:</label>
                                    <select class="form-select" id="tamano" name="tamano" onchange="actualizarPrecioRealTime()">
                                        <option value="personal">Personal (1 comensal - x0.8)</option>
                                        <option value="mediana" selected>Mediana (2-3 comensales - x1.0)</option>
                                        <option value="familiar">Familiar (4 comensales - x1.4)</option>
                                        <option value="jumbo">Jumbo Extra (5-6 comensales - x1.8)</option>
                                    </select>
                                </div>

                                <!-- ASOCIACIÓN: LABEL for="tipo_masa" -> SELECT id="tipo_masa" -->
                                <div class="col-md-4">
                                    <label for="tipo_masa" class="form-label fw-bold">Tipo de Masa:</label>
                                    <select class="form-select" id="tipo_masa" name="tipo_masa" onchange="actualizarPrecioRealTime()">
                                        <option value="tradicional">Tradicional Napolitana (+$0.00)</option>
                                        <option value="delgada">Fina & Crocante (+$0.00)</option>
                                        <option value="borde_queso">Borde Relleno de Queso (+$2.50)</option>
                                        <option value="integral">Masa de Trigo Integral (+$1.50)</option>
                                    </select>
                                </div>

                                <!-- ASOCIACIÓN: LABEL for="cantidad" -> INPUT id="cantidad" -->
                                <div class="col-md-4">
                                    <label for="cantidad" class="form-label fw-bold">Cantidad de Pizzas:</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="cantidad" 
                                           name="cantidad" 
                                           min="1" 
                                           max="20" 
                                           value="1" 
                                           onchange="actualizarPrecioRealTime()"
                                           oninput="actualizarPrecioRealTime()">
                                </div>

                            </div>
                        </fieldset>

                        <!-- AGRUPACIÓN 3: INGREDIENTES EXTRA & NOTAS PARA LA COCINA -->
                        <fieldset class="border p-4 rounded-3 mb-4 bg-light">
                            <legend class="float-none w-auto px-3 fs-6 fw-bold text-danger border rounded bg-white shadow-sm">
                                <i class="fa-solid fa-cheese me-2"></i>3. Ingredientes Extra & Notas para Cocina
                            </legend>

                            <label class="form-label fw-bold d-block mb-2">Selecciona Toppings Adicionales:</label>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6 col-md-4">
                                    <div class="form-check p-2 border rounded bg-white">
                                        <input class="form-check-input extra-checkbox" type="checkbox" name="extras[]" value="extra_queso" id="extra_queso" data-precio="1.50" onchange="actualizarPrecioRealTime()">
                                        <label class="form-check-input-label small fw-bold" for="extra_queso"> Extra Queso (+$1.50)</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="form-check p-2 border rounded bg-white">
                                        <input class="form-check-input extra-checkbox" type="checkbox" name="extras[]" value="champinones" id="champinones" data-precio="1.20" onchange="actualizarPrecioRealTime()">
                                        <label class="form-check-input-label small fw-bold" for="champinones">Champiñones (+$1.20)</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="form-check p-2 border rounded bg-white">
                                        <input class="form-check-input extra-checkbox" type="checkbox" name="extras[]" value="aceitunas" id="aceitunas" data-precio="1.00" onchange="actualizarPrecioRealTime()">
                                        <label class="form-check-input-label small fw-bold" for="aceitunas">Aceitunas (+$1.00)</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="form-check p-2 border rounded bg-white">
                                        <input class="form-check-input extra-checkbox" type="checkbox" name="extras[]" value="bacon" id="bacon" data-precio="2.00" onchange="actualizarPrecioRealTime()">
                                        <label class="form-check-input-label small fw-bold" for="bacon">Tocino Crocante (+$2.00)</label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-4">
                                    <div class="form-check p-2 border rounded bg-white">
                                        <input class="form-check-input extra-checkbox" type="checkbox" name="extras[]" value="jalapenos" id="jalapenos" data-precio="1.00" onchange="actualizarPrecioRealTime()">
                                        <label class="form-check-input-label small fw-bold" for="jalapenos">Jalapeños (+$1.00)</label>
                                    </div>
                                </div>
                            </div>

                            <!-- ASOCIACIÓN: LABEL for="indicaciones" -> TEXTAREA id="indicaciones" -->
                            <div class="col-12">
                                <label for="indicaciones" class="form-label fw-bold">Notas Especiales para el Chef / Cocina:</label>
                                <textarea class="form-control" 
                                          id="indicaciones" 
                                          name="indicaciones" 
                                          rows="3" 
                                          placeholder="Ej. Servir picante por separado, cortar en 8 porciones iguales, masa bien crocante..."></textarea>
                            </div>

                        </fieldset>

                        <!-- Botón de Envío POST -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg py-3 fw-bold shadow">
                                <i class="fa-solid fa-paper-plane me-2"></i>Enviar Pedido a Cocina
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

        <!-- Resumen Dinámico en Tiempo Real (Columna Derecha) -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-lg sticky-top" style="top: 90px;">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="mb-0 brand-font text-warning"><i class="fa-solid fa-calculator me-2"></i>Resumen de Orden en Mesa</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <img id="preview-pizza-img" src="assets/images/<?= htmlspecialchars($pizzaSeleccionada['imagen']) ?>" class="img-fluid rounded shadow-sm border mb-2 max-h-180" alt="Pizza preview">
                        <h6 id="preview-pizza-nombre" class="fw-bold mb-0 text-danger"><?= htmlspecialchars($pizzaSeleccionada['nombre']) ?></h6>
                    </div>

                    <ul class="list-group list-group-flush small mb-3">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Precio Base Unitario:</span>
                            <strong id="calc-base">$<?= number_format($pizzaSeleccionada['precio'], 2) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Masa & Extras:</span>
                            <strong id="calc-extras">$0.00</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal (<span id="calc-cant-lbl">1</span>):</span>
                            <strong id="calc-subtotal">$<?= number_format($pizzaSeleccionada['precio'], 2) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>IVA (12%):</span>
                            <strong id="calc-iva">$<?= number_format($pizzaSeleccionada['precio'] * 0.12, 2) ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between text-success">
                            <span>Servicio en Local:</span>
                            <strong>$0.00 (Incluido)</strong>
                        </li>
                    </ul>

                    <div class="p-3 bg-danger text-white rounded text-center">
                        <small class="text-uppercase text-white-50 d-block">TOTAL EN MESA:</small>
                        <span id="calc-total-final" class="display-6 fw-bold">$<?= number_format($pizzaSeleccionada['precio'] * 1.12, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
