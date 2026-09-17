<div class="container my-5">

    <!-- Encabezado de la Sección de Historial -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <span class="badge bg-danger text-white font-monospace fs-6 px-3 py-2 rounded-pill mb-2">
                <i class="fa-solid fa-clock-rotate-left me-2"></i>Historial de Pedidos en Mesa
            </span>
            <h2 class="display-5 brand-font fw-bold text-dark mb-0">Historial de Pedidos en Mesa</h2>
        </div>
        <div>
            <a href="index.php?action=order" class="btn btn-success btn-lg fw-bold shadow">
                <i class="fa-solid fa-plus me-2"></i>Nuevo Pedido
            </a>
        </div>
    </div>

   

    <?php if (empty($historial)): ?>
        <!-- Estado Vacío -->
        <div class="card border-0 shadow-lg text-center py-5">
            <div class="card-body">
                <div class="display-1 text-muted mb-3"><i class="fa-solid fa-receipt"></i></div>
                <h3 class="fw-bold">Aún no has realizado pedidos en esta sesión</h3>
                <p class="text-muted mb-4">Ingresa al Menú Virtual para seleccionar tus pizzas favoritas y realizar tu primer pedido a mesa.</p>
                <a href="index.php?action=home" class="btn btn-danger btn-lg px-4 fw-bold">
                    <i class="fa-solid fa-pizza-slice me-2"></i>Ir al Menú Virtual
                </a>
            </div>
        </div>
    <?php else: ?>

        <!-- Tarjetas del Historial de Pedidos -->
        <div class="row g-4 mb-5">
            <?php foreach ($historial as $index => $item): ?>
                <div class="col-12">
                    <div class="card border-0 shadow-md overflow-hidden border-start border-4 border-danger">
                        <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-danger fs-6">Ticket #<?= htmlspecialchars($item['codigo_orden']) ?></span>
                                <span class="badge bg-dark"><i class="fa-solid fa-table me-1"></i><?= htmlspecialchars($item['cliente']['numero_mesa']) ?></span>
                                <span class="badge bg-success"><i class="fa-solid fa-fire me-1"></i><?= htmlspecialchars($item['estado']) ?></span>
                            </div>
                            <span class="text-muted small">
                                <i class="fa-regular fa-clock me-1"></i><?= htmlspecialchars($item['fecha_hora']) ?>
                            </span>
                        </div>
                        <div class="card-body p-4">
                            <div class="row align-items-center g-3">
                                
                                <div class="col-md-2 text-center text-md-start">
                                    <img src="assets/images/<?= htmlspecialchars($item['detalle']['pizza_imagen'] ?? 'margherita.jpg') ?>" 
                                         alt="<?= htmlspecialchars($item['detalle']['pizza_nombre']) ?>" 
                                         class="img-fluid rounded shadow-sm border max-h-180">
                                </div>

                                <div class="col-md-7">
                                    <h5 class="fw-bold text-danger mb-1"><?= htmlspecialchars($item['detalle']['pizza_nombre']) ?></h5>
                                    <p class="mb-2 text-muted">
                                        Comensal: <strong><?= htmlspecialchars($item['cliente']['nombre']) ?></strong> | 
                                        Modalidad: <strong><?= ($item['cliente']['tipo_atencion'] == 'en_mesa') ? 'Consumo en Mesa' : 'Para Llevar' ?></strong>
                                    </p>
                                    
                                    <div class="d-flex flex-wrap gap-2 small text-secondary">
                                        <span class="border rounded px-2 py-1 bg-light">Tamaño: <strong><?= htmlspecialchars($item['detalle']['tamano']) ?></strong></span>
                                        <span class="border rounded px-2 py-1 bg-light">Masa: <strong><?= htmlspecialchars($item['detalle']['tipo_masa']) ?></strong></span>
                                        <span class="border rounded px-2 py-1 bg-light">Cantidad: <strong><?= htmlspecialchars($item['detalle']['cantidad']) ?></strong></span>
                                        <?php if (!empty($item['detalle']['extras'])): ?>
                                            <span class="border rounded px-2 py-1 bg-light text-success">Extras: <strong><?= implode(', ', $item['detalle']['extras']) ?></strong></span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if (!empty($item['detalle']['indicaciones'])): ?>
                                        <div class="mt-2 text-muted small fst-italic">
                                            <i class="fa-solid fa-comment-dots me-1 text-warning"></i>Nota: "<?= htmlspecialchars($item['detalle']['indicaciones']) ?>"
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-3 text-md-end border-start-md">
                                    <small class="text-muted d-block">Subtotal: $<?= number_format($item['financiero']['subtotal'], 2) ?></small>
                                    <small class="text-muted d-block">IVA (12%): $<?= number_format($item['financiero']['iva'], 2) ?></small>
                                    <span class="fs-3 fw-bold text-success d-block my-1">$<?= number_format($item['financiero']['total'], 2) ?></span>
                                    <span class="badge bg-secondary text-uppercase"><?= htmlspecialchars($item['cliente']['metodo_pago']) ?></span>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

       
    <?php endif; ?>

</div>
