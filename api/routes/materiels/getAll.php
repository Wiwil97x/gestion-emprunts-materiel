<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/Database.php';
require_once '../../models/Materiel.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Instancier l’objet Materiel
$materiel = new Materiel($db);

// Récupérer tous les matériels
$stmt = $materiel->getAll();
$num = $stmt->rowCount();

if ($num > 0) {
    $materiels_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $materiels_arr[] = [
            "id" => $id,
            "nom" => $nom
        ];
    }

    echo json_encode($materiels_arr);
} else {
    echo json_encode(["message" => "Aucun matériel trouvé."]);
}
?>
