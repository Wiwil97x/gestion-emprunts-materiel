<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
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

if (!empty($data->id_utilisateur) && !empty($data->id_materiel)) {
    $emprunt->id_utilisateur = $data->id_utilisateur;
    $emprunt->id_materiel = $data->id_materiel;

    if ($emprunt->create()) {
        http_response_code(201);
        echo json_encode(["message" => "Emprunt enregistré avec succès."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Impossible d'enregistrer l'emprunt."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Données incomplètes."]);
}
?>
