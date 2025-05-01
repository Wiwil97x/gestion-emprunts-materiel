<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once '../../config/Database.php';
require_once '../../models/Utilisateur.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Instancier l’objet Utilisateur
$utilisateur = new Utilisateur($db);

// Récupérer tous les utilisateurs
$stmt = $utilisateur->getAll();
$num = $stmt->rowCount();

if ($num > 0) {
    $utilisateurs_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $utilisateurs_arr[] = [
            "id" => $id,
            "nom" => $nom,
            "prenom" => $prenom
        ];
    }

    echo json_encode($utilisateurs_arr);
} else {
    echo json_encode(["message" => "Aucun utilisateur trouvé."]);
}
?>
