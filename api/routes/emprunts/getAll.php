<?php
// Headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Inclure les fichiers nécessaires
require_once '../../config/Database.php';
require_once '../../models/Emprunt.php';

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();

// Instancier l’objet Emprunt
$emprunt = new Emprunt($db);

// Récupérer tous les emprunts rendus
$stmt = $emprunt->getAll();
$num = $stmt->rowCount();

if ($num > 0) {
    $emprunts_arr = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        // 🕒 Formatter avec date + heure
        $date_emprunt_formattee = date("d-m-Y H:i:s", strtotime($date_emprunt));
        $date_retour_effectif_formattee = !empty($date_retour_effectif) 
            ? date("d-m-Y H:i:s", strtotime($date_retour_effectif)) 
            : "Non retourné";

        $emprunts_arr[] = [
            "id" => $id,
            "utilisateur" => $nom_utilisateur . " " . $prenom_utilisateur,
            "materiel" => $nom_materiel,
            "date_emprunt" => $date_emprunt_formattee, 
            "date_retour_effectif" => $date_retour_effectif_formattee, 
            "statut" => $statut
        ];
    }

    echo json_encode($emprunts_arr);
} else {
    echo json_encode([]); 
}
?>
