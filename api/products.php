<?php
require_once '../middlewares/cors.php';
require_once '../db.php';

header('Content-Type: application/json');

try {
  $sql = "SELECT * FROM products ORDER BY id_items_showcase ASC LIMIT 150";
  $stmt = $pdo->query($sql);
  $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $products = array_map(function ($row) {
    return [
      'Id_items_showcase' => (int) $row['id_items_showcase'],
      'Id_showcase' => (int) $row['id_showcase'],
      'Name_items_showcase' => $row['name_items_showcase'],
      'slug' => $row['slug'],
      'Status_items_showcase' => $row['status_items_showcase'],
      'Price_items_showcase' => $row['price_items_showcase'] !== null ? floatval($row['price_items_showcase']) : null,
      'Original_price_items_showcase' => $row['original_price_items_showcase'] !== null ? floatval($row['original_price_items_showcase']) : null,
      'Discount_items_showcase' => $row['discount_items_showcase'] !== null ? floatval($row['discount_items_showcase']) : null,
      'Min_price_offeritems_showcase' => $row['min_price_offeritems_showcase'] !== null ? floatval($row['min_price_offeritems_showcase']) : null,
      'Delivery_items_showcase' => $row['delivery_items_showcase'],
      'Height_items_showcase' => $row['height_items_showcase'] !== null ? floatval($row['height_items_showcase']) : null,
      'Width_items_showcase' => $row['width_items_showcase'] !== null ? floatval($row['width_items_showcase']) : null,
      'Length_items_showcase' => $row['length_items_showcase'] !== null ? floatval($row['length_items_showcase']) : null,
      'Id_origin' => $row['id_origin'],
      'user_type_id' => $row['user_type_id'],
      'fullname' => $row['fullname'],
      'Images_items_showcase' => $row['images_items_showcase'] ? json_decode($row['images_items_showcase'], true) : null,
      'Image_crop_items_showcase' => $row['image_crop'] ? $row['image_crop'] : null,
'Additional_Images_crop_items_showcase' => $row['additional_images_crop'] ? $row['additional_images_crop'] : null,
      'fav' => isset($row['fav']) ? filter_var($row['fav'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null,
    ];
  }, $rows);

  echo json_encode($products, JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Error al obtener los productos'], JSON_UNESCAPED_UNICODE);
}
