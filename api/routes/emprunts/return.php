<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once '../../config/Database.php';
require_once '../../models/Emprunt.php';

$database = new Database();
$db = $database->getConnection();
$emprunt = new Emprunt($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->id)) {  // 🔄 Remplacer "id_emprunt" par "id"
    $emprunt->id = $data->id;
    
    if ($emprunt->returnEmprunt()) {
        echo json_encode(["message" => "Emprunt retourné avec succès."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Impossible de retourner l'emprunt."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Données incomplètes."]);
}
?>
