<?php
class Materiel {
    private $conn;
    private $table_name = "materiels";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Méthode pour récupérer tous les matériels
    public function getAll() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>
