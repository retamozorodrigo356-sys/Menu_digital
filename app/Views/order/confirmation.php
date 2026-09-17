<div class="container my-5">

    <!-- Encabezado de Confirmación POST para Local -->
    <div class="text-center mb-4">
        <div class="display-1 text-success mb-2">
            <i class="fa-solid fa-bell-concierge"></i>
        </div>
        <span class="badge bg-success font-monospace fs-6 px-3 py-2 rounded-pill mb-2">
            <i class="fa-solid fa-server me-2"></i>Pedido Enviado a Cocina mediante
        </span>
        <h2 class="display-5 brand-font fw-bold text-dark">¡Pedido Registrado para tu Mesa!</h2>
        <p class="text-muted">Tu orden se ha guardado en la cocina del local y se ha agregado a tu historial en sesión de navegación.</p>
    </div>

    <!-- TICKET DE COMPRA Y DETALLE DE LA ORDEN DE MESA -->
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg position-relative overflow-hidden">
                <div class="card-header bg-danger text-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold"><i class="fa-solid fa-utensils me-2"></i>Ticket de Mesa #<?= htmlspecialchars($ordenProcesada['codigo_orden']) ?></h5>
                    </div>
                    <span class="badge bg-white text-danger font-monospace"><?= htmlspecialchars($ordenProcesada['fecha_hora']) ?></span>
                </div>
                <div class="card-body p-4 p-md-5">

                    <!-- Información del Cliente y Mesa -->
                    <h6 class="text-uppercase fw-bold text-danger border-bottom pb-2 mb-3">
                        <i class="fa-solid fa-chair me-2"></i>Ubicación en Salón & Comensal
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Comensal:</small>
                            <strong><?= htmlspecialchars($ordenProcesada['cliente']['nombre']) ?></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Ubicación / Mesa:</small>
                            <strong class="text-danger fs-5"><i class="fa-solid fa-table me-1"></i><?= htmlspecialchars($ordenProcesada['cliente']['numero_mesa']) ?></strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Modalidad:</small>
                            <span class="badge bg-success"><?= ($ordenProcesada['cliente']['tipo_atencion'] == 'en_mesa') ? 'Consumo en Salón (Mesa)' : 'Para Llevar en Local' ?></span>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Forma de Pago:</small>
                            <strong><?= htmlspecialchars(strtoupper($ordenProcesada['cliente']['metodo_pago'])) ?></strong>
                        </div>
                    </div>

                    <!-- Detalle del Producto -->
                    <h6 class="text-uppercase fw-bold text-danger border-bottom pb-2 mb-3">
                        <i class="fa-solid fa-pizza-slice me-2"></i>Detalle de la Pizza Solicitada
                    </h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Producto</th>
                                    <th>Especificaciones</th>
                                    <th class="text-center">Cant.</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="fw-bold text-danger"><?= htmlspecialchars($ordenProcesada['detalle']['pizza_nombre']) ?></td>
                                    <td>
                                        <ul class="mb-0 small text-muted ps-3">
                                            <li>Tamaño: <strong><?= htmlspecialchars($ordenProcesada['detalle']['tamano']) ?></strong></li>
                                            <li>Masa: <strong><?= htmlspecialchars($ordenProcesada['detalle']['tipo_masa']) ?></strong></li>
                                            <?php if (!empty($ordenProcesada['detalle']['extras'])): ?>
                                                <li>Extras: <strong><?= implode(', ', $ordenProcesada['detalle']['extras']) ?></strong></li>
                                            <?php endif; ?>
                                            <?php if (!empty($ordenProcesada['detalle']['indicaciones'])): ?>
                                                <li>Notas para Cocina: <em>"<?= htmlspecialchars($ordenProcesada['detalle']['indicaciones']) ?>"</em></li>
                                            <?php endif; ?>
                                        </ul>
                                    </td>
                                    <td class="text-center fw-bold fs-5"><?= htmlspecialchars($ordenProcesada['detalle']['cantidad']) ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Desglose Financiero en Local -->
                    <div class="bg-light p-4 rounded-3 border mb-4">
                        <div class="row text-end g-2">
                            <div class="col-8 text-muted">Subtotal Pizzas:</div>
                            <div class="col-4 fw-bold">$<?= number_format($ordenProcesada['financiero']['subtotal'], 2) ?></div>

                            <div class="col-8 text-muted">Impuesto IVA (12%):</div>
                            <div class="col-4 fw-bold">$<?= number_format($ordenProcesada['financiero']['iva'], 2) ?></div>

                            <div class="col-8 text-muted">Servicio en Local:</div>
                            <div class="col-4 fw-bold text-success">$0.00 (Gratis)</div>

                            <div class="col-12"><hr class="my-2"></div>

                            <div class="col-8 fs-5 fw-bold text-dark">TOTAL A PAGAR EN MESA:</div>
                            <div class="col-4 fs-4 fw-bold text-danger">$<?= number_format($ordenProcesada['financiero']['total'], 2) ?></div>
                        </div>
                    </div>

                    <div class="text-center d-flex gap-2 justify-content-center flex-wrap">
                        <a href="index.php?action=home" class="btn btn-outline-secondary px-3">
                            <i class="fa-solid fa-pizza-slice me-1"></i>Ir al Menú Virtual
                        </a>
                        <a href="index.php?action=history" class="btn btn-info text-white px-3 fw-bold">
                            <i class="fa-solid fa-clock-rotate-left me-1"></i>Ver Historial de Mis Pedidos
                        </a>
                        <a href="index.php?action=order" class="btn btn-success px-3">
                            <i class="fa-solid fa-plus me-1"></i>Pedir Otra Pizza
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

   

</div>
