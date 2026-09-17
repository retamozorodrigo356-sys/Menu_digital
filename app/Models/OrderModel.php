<?php

namespace App\Models;

/**
 * OrderModel - Modelo para el procesamiento de Pedidos en Local (POST & Historial en Sesión)
 * Maneja la atención en mesa y el historial de pedidos en sesión.
 */
class OrderModel {
    
    private const TAMANO_MULTIPLICADOR = [
        'personal' => 0.8,
        'mediana' => 1.0,
        'familiar' => 1.4,
        'jumbo' => 1.8
    ];

    private const MASA_PRECIOS = [
        'tradicional' => 0.00,
        'delgada' => 0.00,
        'borde_queso' => 2.50,
        'integral' => 1.50
    ];

    private const EXTRAS_PRECIOS = [
        'extra_queso' => 1.50,
        'champinones' => 1.20,
        'aceitunas' => 1.00,
        'bacon' => 2.00,
        'jalapenos' => 1.00
    ];

    /**
     * Procesar y calcular la orden de atención en mesa desde la variable $_POST
     */
    public function createOrder(array $postData, array $pizzaBase): array {
        $tamanoKey = $postData['tamano'] ?? 'mediana';
        $masaKey = $postData['tipo_masa'] ?? 'tradicional';
        $cantidad = max(1, (int)($postData['cantidad'] ?? 1));
        
        $multiplicadorTamano = self::TAMANO_MULTIPLICADOR[$tamanoKey] ?? 1.0;
        $precioMasaExtra = self::MASA_PRECIOS[$masaKey] ?? 0.0;

        // Calcular precio de la pizza base adaptado al tamaño
        $precioUnitarioBase = ($pizzaBase['precio'] * $multiplicadorTamano) + $precioMasaExtra;

        // Calcular extras seleccionados
        $extrasSeleccionados = $postData['extras'] ?? [];
        $totalExtrasUnitario = 0.0;
        $listaExtrasNombres = [];

        foreach ($extrasSeleccionados as $extraKey) {
            if (isset(self::EXTRAS_PRECIOS[$extraKey])) {
                $totalExtrasUnitario += self::EXTRAS_PRECIOS[$extraKey];
                $listaExtrasNombres[] = ucfirst(str_replace('_', ' ', $extraKey));
            }
        }

        $subtotal = ($precioUnitarioBase + $totalExtrasUnitario) * $cantidad;
        $impuestoIVA = $subtotal * 0.12; // 12% IVA
        $totalFinal = $subtotal + $impuestoIVA;

        // Generar número de ticket único para la cocina/mesa
        $codigoOrden = 'MESA-' . strtoupper(substr(md5(uniqid()), 0, 6));

        return [
            'codigo_orden' => $codigoOrden,
            'fecha_hora' => date('Y-m-d H:i:s'),
            'estado' => 'En Preparación en Cocina 🍳',
            'cliente' => [
                'nombre' => htmlspecialchars(trim($postData['nombre'] ?? '')),
                'numero_mesa' => htmlspecialchars(trim($postData['numero_mesa'] ?? 'Mesa 1')),
                'tipo_atencion' => htmlspecialchars($postData['tipo_atencion'] ?? 'en_mesa'),
                'metodo_pago' => htmlspecialchars($postData['metodo_pago'] ?? 'efectivo')
            ],
            'detalle' => [
                'pizza_nombre' => $pizzaBase['nombre'],
                'pizza_imagen' => $pizzaBase['imagen'],
                'tamano' => ucfirst($tamanoKey),
                'tipo_masa' => ucfirst(str_replace('_', ' ', $masaKey)),
                'cantidad' => $cantidad,
                'extras' => $listaExtrasNombres,
                'indicaciones' => htmlspecialchars(trim($postData['indicaciones'] ?? ''))
            ],
            'financiero' => [
                'subtotal' => round($subtotal, 2),
                'iva' => round($impuestoIVA, 2),
                'total' => round($totalFinal, 2)
            ]
        ];
    }

    /**
     * Valida los datos requeridos enviados por POST para la mesa
     */
    public function validate(array $postData): array {
        $errores = [];

        if (empty(trim($postData['nombre'] ?? ''))) {
            $errores[] = 'El nombre del comensal es obligatorio.';
        }
        if (empty(trim($postData['numero_mesa'] ?? ''))) {
            $errores[] = 'El número de mesa o indicación de local es obligatorio.';
        }
        if (empty($postData['pizza_id'])) {
            $errores[] = 'Debe seleccionar una pizza del menú virtual.';
        }

        return $errores;
    }

    /**
     * Guarda la orden en el historial de sesión (acumulativo)
     */
    public function guardarEnHistorialSesion(array $orden): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['historial_pedidos'])) {
            $_SESSION['historial_pedidos'] = [];
        }

        // Agregar al inicio del historial
        array_unshift($_SESSION['historial_pedidos'], $orden);
        $_SESSION['pedido_temporal_local'] = $orden;
    }

    /**
     * Obtiene todos los pedidos realizados en la sesión actual
     */
    public function obtenerHistorial(): array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['historial_pedidos'] ?? [];
    }

    public function obtenerUltimoPedido(): ?array {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['pedido_temporal_local'] ?? null;
    }
}
