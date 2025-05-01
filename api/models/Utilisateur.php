<?php
require_once __DIR__ . '/../config/Database.php';

class Utilisateur {
    private $conn;
    private $table_name = "utilisateurs";

    public $id;
    public $nom;
    public $prenom;
    public $email;
    public $mot_de_passe;
    public $role;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (nom, prenom, email, mot_de_passe, role) VALUES (:nom, :prenom, :email, :mot_de_passe, :role)";
        $stmt = $this->conn->prepare($query);

        $this->nom = htmlspecialchars(strip_tags($this->nom));
        $this->prenom = htmlspecialchars(strip_tags($this->prenom));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->mot_de_passe = password_hash($this->mot_de_passe, PASSWORD_BCRYPT); /*Quand un mot de passe est inséré, il est automatiquement hashé pour la sécurité.*/
        $this->role = htmlspecialchars(strip_tags($this->role));

        $stmt->bindParam(":nom", $this->nom);
        $stmt->bindParam(":prenom", $this->prenom);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":mot_de_passe", $this->mot_de_passe);
        $stmt->bindParam(":role", $this->role);

        return $stmt->execute();
    }

    public function getIdByName($nom, $prenom) {
        $query = "SELECT id FROM utilisateurs WHERE nom = :nom AND prenom = :prenom LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['id'] : null;
    }
    
    public function createSimple($nom, $prenom) {
        $query = "INSERT INTO utilisateurs (nom, prenom, role) VALUES (:nom, :prenom, 'utilisateur')";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nom", $nom);
        $stmt->bindParam(":prenom", $prenom);
    
        if ($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return null;
    }
    
    public function getAll() {
        $query = "SELECT id, nom, prenom, email, role FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
      
}
?>
