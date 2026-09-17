<?php

namespace App\Models;

/**
 * PizzaModel - Clase Modelo del menú de pizzas
 * Representa la capa de acceso a datos del catálogo de la Pizzería con persistencia JSON y gestión de imágenes.
 */
class PizzaModel {
    private array $pizzas;
    private string $jsonPath;
    private string $uploadDir;

    public function __construct() {
        $this->jsonPath = __DIR__ . '/../data/pizzas.json';
        $this->uploadDir = __DIR__ . '/../../public/assets/images/';

        $this->loadData();
    }

    /**
     * Carga los datos del catálogo de pizzas desde el archivo JSON
     */
    private function loadData(): void {
        if (file_exists($this->jsonPath)) {
            $jsonContent = file_get_contents($this->jsonPath);
            $data = json_decode($jsonContent, true);
            if (is_array($data)) {
                $this->pizzas = $data;
                return;
            }
        }

        // Si no existe el archivo JSON, inicializar con el catálogo por defecto
        $this->pizzas = [
            [
                'id' => 1,
                'nombre' => 'Pizza Margherita Tradizionale',
                'categoria' => 'artesanal',
                'precio' => 12.50,
                'descripcion' => 'Salsa de tomate napolitana, mozzarella di bufala fresca, albahaca fresca y aceite de oliva virgen extra.',
                'ingredientes' => ['Tomate', 'Mozzarella', 'Albahaca', 'Aceite de Oliva'],
                'calorias' => 780,
                'imagen' => 'margherita.jpg',
                'popular' => true
            ],
            [
                'id' => 2,
                'nombre' => 'Pepperoni Speciale',
                'categoria' => 'clasica',
                'precio' => 14.00,
                'descripcion' => 'Doble porción de pepperoni crocante, queso mozzarella fundido y orégano silvestre italiano.',
                'ingredientes' => ['Pepperoni', 'Mozzarella', 'Salsa Especial', 'Orégano'],
                'calorias' => 950,
                'imagen' => 'pepperoni.jpg',
                'popular' => true
            ],
            [
                'id' => 3,
                'nombre' => 'Quattro Formaggi Gourmet',
                'categoria' => 'gourmet',
                'precio' => 16.50,
                'descripcion' => 'Exquisita mezcla de 4 quesos: Gorgonzola madurado, Mozzarella, Parmigiano Reggiano y Fontina.',
                'ingredientes' => ['Gorgonzola', 'Mozzarella', 'Parmesano', 'Fontina'],
                'calorias' => 1020,
                'imagen' => 'quattro_formaggi.jpg',
                'popular' => false
            ],
            [
                'id' => 4,
                'nombre' => 'BBQ Chicken & Smoke',
                'categoria' => 'especial',
                'precio' => 15.50,
                'descripcion' => 'Pechuga de pollo a la parrilla, salsa BBQ ahumada, cebolla morada caramelizada y queso cheddar.',
                'ingredientes' => ['Pollo a la Parrilla', 'Salsa BBQ', 'Cebolla Morada', 'Cheddar'],
                'calorias' => 890,
                'imagen' => 'bbq_chicken.jpg',
                'popular' => false
            ]
        ];

        $this->saveData();
    }

    /**
     * Guarda el estado actual del catálogo en el archivo JSON
     */
    private function saveData(): bool {
        $dir = dirname($this->jsonPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        return file_put_contents($this->jsonPath, json_encode($this->pizzas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * Obtener todas las pizzas
     */
    public function getAll(): array {
        return $this->pizzas;
    }

    /**
     * Buscar pizza por ID
     */
    public function getById(int $id): ?array {
        foreach ($this->pizzas as $pizza) {
            if ($pizza['id'] === $id) {
                return $pizza;
            }
        }
        return null;
    }

    /**
     * Filtrar pizzas según los parámetros recibidos por el método GET
     */
    public function filterPizzas(string $search = '', string $category = '', float $maxPrice = 0.0): array {
        return array_values(array_filter($this->pizzas, function($pizza) use ($search, $category, $maxPrice) {
            $matchSearch = empty($search) || 
                stripos($pizza['nombre'], $search) !== false || 
                stripos($pizza['descripcion'], $search) !== false;

            $matchCategory = empty($category) || strtolower($pizza['categoria']) === strtolower($category);

            $matchPrice = ($maxPrice <= 0) || ($pizza['precio'] <= $maxPrice);

            return $matchSearch && $matchCategory && $matchPrice;
        }));
    }

    /**
     * Obtener listado de categorías/variedades disponibles (estáticas + dinámicas existentes)
     */
    public function getCategories(): array {
        $baseCategories = [
            'artesanal' => 'Artesanales Napolitanas',
            'clasica' => 'Clásicas Populares',
            'gourmet' => 'Selección Gourmet',
            'especial' => 'Especiales de la Casa'
        ];

        // Recolectar categorías personalizadas de las pizzas guardadas
        foreach ($this->pizzas as $pizza) {
            $catKey = strtolower(trim($pizza['categoria']));
            if (!isset($baseCategories[$catKey]) && !empty($catKey)) {
                $baseCategories[$catKey] = ucfirst($pizza['categoria']);
            }
        }

        return $baseCategories;
    }

    /**
     * Guardar y registrar una nueva pizza en el sistema con subida de imagen
     */
    public function savePizza(array $postData, array $fileData = []): array {
        $errors = [];

        $nombre = trim($postData['nombre'] ?? '');
        $categoriaSel = trim($postData['categoria'] ?? '');
        $nuevaCategoria = trim($postData['nueva_categoria'] ?? '');
        $categoria = !empty($nuevaCategoria) ? $nuevaCategoria : $categoriaSel;
        $precio = (float)($postData['precio'] ?? 0);
        $descripcion = trim($postData['descripcion'] ?? '');
        $ingredientesStr = trim($postData['ingredientes'] ?? '');
        $calorias = (int)($postData['calorias'] ?? 0);
        $popular = isset($postData['popular']) && ($postData['popular'] == '1' || $postData['popular'] == 'on');

        // Validaciones
        if (empty($nombre)) {
            $errors[] = 'El nombre de la pizza es obligatorio.';
        }
        if (empty($categoria)) {
            $errors[] = 'Debes seleccionar o ingresar una variedad / categoría para la pizza.';
        }
        if ($precio <= 0) {
            $errors[] = 'El precio debe ser un número mayor a 0.';
        }
        if (empty($descripcion)) {
            $errors[] = 'La descripción es requerida para dar detalle a los clientes.';
        }
        if (empty($ingredientesStr)) {
            $errors[] = 'Debes ingresar al menos un ingrediente (separados por coma).';
        }

        // Procesamiento de Ingredientes
        $ingredientes = array_values(array_filter(array_map('trim', explode(',', $ingredientesStr))));

        // Procesamiento de Imagen
        $nombreImagen = 'margherita.jpg'; // Imagen por defecto de respaldo

        if (isset($fileData['imagen']) && $fileData['imagen']['error'] === UPLOAD_ERR_OK) {
            $file = $fileData['imagen'];
            $fileTmpPath = $file['tmp_name'];
            $fileName = $file['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

            if (!in_array($fileExtension, $allowedExtensions)) {
                $errors[] = 'Formato de imagen no válido. Formatos permitidos: JPG, JPEG, PNG, WEBP, GIF.';
            } else {
                if (!is_dir($this->uploadDir)) {
                    mkdir($this->uploadDir, 0777, true);
                }

                $newFileName = 'pizza_' . time() . '_' . rand(100, 999) . '.' . $fileExtension;
                $destPath = $this->uploadDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    $nombreImagen = $newFileName;
                } else {
                    $errors[] = 'Ocurrió un error al guardar la imagen en el servidor.';
                }
            }
        } elseif (!empty($postData['imagen_preset'])) {
            // Si el usuario seleccionó una imagen preseteada del sistema
            $nombreImagen = trim($postData['imagen_preset']);
        }

        if (!empty($errors)) {
            return [
                'success' => false,
                'errors' => $errors
            ];
        }

        // Calcular nuevo ID auto-incremental
        $maxId = 0;
        foreach ($this->pizzas as $p) {
            if ($p['id'] > $maxId) {
                $maxId = $p['id'];
            }
        }
        $newId = $maxId + 1;

        $nuevaPizza = [
            'id' => $newId,
            'nombre' => $nombre,
            'categoria' => strtolower($categoria),
            'precio' => $precio,
            'descripcion' => $descripcion,
            'ingredientes' => $ingredientes,
            'calorias' => $calorias > 0 ? $calorias : 800,
            'imagen' => $nombreImagen,
            'popular' => $popular
        ];

        // Agregar al listado y guardar en el archivo JSON
        $this->pizzas[] = $nuevaPizza;
        $this->saveData();

        return [
            'success' => true,
            'pizza' => $nuevaPizza
        ];
    }
}

