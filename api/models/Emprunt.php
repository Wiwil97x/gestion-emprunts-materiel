<?php
class Emprunt {
    private $conn;
    private $table_name = "emprunts";

    public $id;
    public $id_utilisateur;
    public $id_materiel;
    public $date_emprunt;
    public $date_retour_prevu;
    public $date_retour_effectif;
    public $statut;

    public function __construct($db) {
        $this->conn = $db;
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->table_name = "emprunts";
    }

    // Fonction pour créer un emprunt
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (id_utilisateur, id_materiel, date_emprunt, date_retour_effectif, statut) 
                  VALUES (:id_utilisateur, :id_materiel, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), 'en cours')";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":id_utilisateur", $this->id_utilisateur);
        $stmt->bindParam(":id_materiel", $this->id_materiel);

        return $stmt->execute();
    }

    public function getAll() {
        $query = "SELECT 
                    e.id, 
                    u.nom AS nom_utilisateur, 
                    u.prenom AS prenom_utilisateur,
                    m.nom AS nom_materiel, 
                    e.date_emprunt, 
                    e.date_retour_effectif, 
                    e.statut 
                  FROM " . $this->table_name . " e
                  JOIN utilisateurs u ON e.id_utilisateur = u.id
                  JOIN materiels m ON e.id_materiel = m.id
                  WHERE e.statut IN ('en cours', 'rendu') 
                  ORDER BY e.date_emprunt DESC"; // Trie du plus récent au plus ancien
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function returnEmprunt() {
        $query = "UPDATE " . $this->table_name . " 
                  SET statut = 'rendu', date_retour_effectif = NOW() 
                  WHERE id = :id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id);
    
        return $stmt->execute();
    }

    public function getHistory() {
        $query = "SELECT 
                    e.id, 
                    u.nom AS nom_utilisateur, 
                    u.prenom AS prenom_utilisateur,
                    m.nom AS nom_materiel, 
                    e.date_emprunt, 
                    e.date_retour_effectif, 
                    e.statut 
                  FROM " . $this->table_name . " e
                  JOIN utilisateurs u ON e.id_utilisateur = u.id
                  JOIN materiels m ON e.id_materiel = m.id
                  WHERE e.statut = 'rendu'";
    
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
