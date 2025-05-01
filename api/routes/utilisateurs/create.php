<?php
require_once '../../models/Utilisateur.php';
$database = new Database();
$db = $database->getConnection();
$user = new Utilisateur($db);

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->nom) && !empty($data->email) && !empty($data->mot_de_passe) && !empty($data->role)) {
    $user->nom = $data->nom;
    $user->prenom = $data->prenom;
    $user->email = $data->email;
    $user->mot_de_passe = $data->mot_de_passe;
    $user->role = $data->role;

    if ($user->create()) {
        http_response_code(201);
        echo json_encode(["message" => "Utilisateur créé avec succès."]);
    } else {
        http_response_code(503);
        echo json_encode(["message" => "Impossible de créer l'utilisateur."]);
    }
} else {
    http_response_code(400);
    echo json_encode(["message" => "Données incomplètes."]);
}
?>
