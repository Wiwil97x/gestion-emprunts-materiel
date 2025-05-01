<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


// Inclure les fichiers nécessaires
require_once '../../config/Database.php';
require_once '../../models/Emprunt.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Instancier l’objet Emprunt
$emprunt = new Emprunt($db);

// Récupérer l’historique des emprunts
$stmt = $emprunt->getHistory();
$num = $stmt->rowCount();

if ($num > 0) {
    $historique_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);
        $historique_arr[] = [
            "id" => $id,
            "utilisateur" => $nom_utilisateur . " " . $prenom_utilisateur,
            "materiel" => $nom_materiel,
            "date_emprunt" => $date_emprunt,
            "date_retour_effectif" => $date_retour_effectif,
            "statut" => $statut
        ];
    }
    echo json_encode($historique_arr);
} else {
    echo json_encode(["message" => "Aucun historique trouvé."]);
}
?>
