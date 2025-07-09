<?php
require_once 'db.php';

try {
  // Verificar si la tabla existe
  $result = $pdo->query("
    SELECT EXISTS (
      SELECT FROM information_schema.tables 
      WHERE table_schema = 'public' AND table_name = 'products'
    )
  ");
  $exists = $result->fetchColumn();

  if (!$exists) {
    $pdo->exec("
      CREATE TABLE products (
        id_items_showcase INT PRIMARY KEY,
        id_showcase INT,
        name_items_showcase VARCHAR(255) NOT NULL,
        slug VARCHAR(255) NOT NULL,
        status_items_showcase VARCHAR(20),
        price_items_showcase NUMERIC,
        original_price_items_showcase NUMERIC,
        discount_items_showcase NUMERIC,
        min_price_offeritems_showcase NUMERIC,
        delivery_items_showcase VARCHAR(50),
        height_items_showcase NUMERIC,
        width_items_showcase NUMERIC,
        length_items_showcase NUMERIC,
        id_origin VARCHAR(50),
        user_type_id VARCHAR(50),
        fullname VARCHAR(255),
        images_items_showcase TEXT,
        image_crop JSONB,
        additional_images_crop JSONB,
        fav BOOLEAN
      );
    ");
  }

  // Verificar si hay datos
  $stmt = $pdo->query("SELECT COUNT(*) FROM products");
  $count = $stmt->fetchColumn();

  if ($count > 0) {
    return; // Ya hay datos, salir silenciosamente
  }

  // Leer archivo JSON
  $dataFile = __DIR__ . '/data.json';
  if (!file_exists($dataFile)) return;

  $jsonData = file_get_contents($dataFile);
  $items = json_decode($jsonData, true);

  if (!is_array($items)) return;

  // Insertar productos
  $sql = "
    INSERT INTO products (
      id_items_showcase, id_showcase, name_items_showcase, slug, status_items_showcase,
      price_items_showcase, original_price_items_showcase, discount_items_showcase, min_price_offeritems_showcase,
      delivery_items_showcase, height_items_showcase, width_items_showcase, length_items_showcase,
      id_origin, user_type_id, fullname, images_items_showcase, image_crop, additional_images_crop, fav
    ) VALUES (
      :id_items_showcase, :id_showcase, :name_items_showcase, :slug, :status_items_showcase,
      :price_items_showcase, :original_price_items_showcase, :discount_items_showcase, :min_price_offeritems_showcase,
      :delivery_items_showcase, :height_items_showcase, :width_items_showcase, :length_items_showcase,
      :id_origin, :user_type_id, :fullname, :images_items_showcase, :image_crop, :additional_images_crop, :fav
    )
    ON CONFLICT (id_items_showcase) DO NOTHING;
  ";

  $stmt = $pdo->prepare($sql);

  foreach ($items as $item) {
    $stmt->execute([
      ':id_items_showcase' => $item['Id_items_showcase'] ?? null,
      ':id_showcase' => $item['Id_showcase'] ?? null,
      ':name_items_showcase' => $item['Name_items_showcase'] ?? null,
      ':slug' => $item['slug'] ?? null,
      ':status_items_showcase' => $item['Status_items_showcase'] ?? null,
      ':price_items_showcase' => $item['Price_items_showcase'] ?? null,
      ':original_price_items_showcase' => $item['Original_price_items_showcase'] ?? null,
      ':discount_items_showcase' => $item['Discount_items_showcase'] ?? null,
      ':min_price_offeritems_showcase' => $item['Min_price_offeritems_showcase'] ?? null,
      ':delivery_items_showcase' => $item['Delivery_items_showcase'] ?? null,
      ':height_items_showcase' => $item['Height_items_showcase'] ?? null,
      ':width_items_showcase' => $item['Width_items_showcase'] ?? null,
      ':length_items_showcase' => $item['Length_items_showcase'] ?? null,
      ':id_origin' => $item['Id_origin'] ?? null,
      ':user_type_id' => $item['user_type_id'] ?? null,
      ':fullname' => $item['fullname'] ?? null,
      ':images_items_showcase' => $item['Images_items_showcase'] ?? null,
      ':image_crop' => isset($item['Image_crop_items_showcase']) ? json_encode($item['Image_crop_items_showcase']) : null,
      // ':additional_images_crop' => isset($item['Additional_Images_crop_items_showcase']) ? json_encode($item['Additional_Images_crop_items_showcase']) : null,
      ':additional_images_crop' => isset($item['Additional_Images_crop_items_showcase']) 
    ? json_encode($item['Additional_Images_crop_items_showcase'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) 
    : null,
      ':fav' => isset($item['fav']) ? (bool)$item['fav'] : null,
    ]);
  }
} catch (Exception $e) {
  // No hacer nada (modo silencioso), o loguearlo si querés
}
