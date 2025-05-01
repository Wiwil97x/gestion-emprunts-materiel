import React, { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom"; // Import pour la redirection
import Swal from "sweetalert2";
import Api from "../services/Api";

const AjouterEmprunt = () => {
  const [utilisateur, setUtilisateur] = useState("");
  const [materiel, setMateriel] = useState("");
  const [utilisateurs, setUtilisateurs] = useState([]);
  const [materiels, setMateriels] = useState([]);

  const navigate = useNavigate(); // Hook pour la navigation

  // Récupérer la liste des utilisateurs et matériels
  useEffect(() => {
    Api.get("/utilisateurs/getAll.php")
      .then((res) => setUtilisateurs(res.data))
      .catch((err) =>
        console.error("❌ Erreur chargement utilisateurs :", err)
      );

    Api.get("/materiels/getAll.php")
      .then((res) => {
        console.log("📦 Données reçues (matériels) :", res.data);
        if (Array.isArray(res.data)) {
          setMateriels(res.data);
        } else {
          console.error("⚠️ Données reçues ne sont pas un tableau :", res.data);
          setMateriels([]); // Pour éviter un crash
        }
      })
      .catch((err) => console.error("❌ Erreur chargement matériels :", err));
  }, []);

  const handleSubmit = (e) => {
    e.preventDefault();

    Api.post("/emprunts/create.php", {
      id_utilisateur: utilisateur,
      id_materiel: materiel,
    })
      .then((res) => {
        // ✅ Afficher l'alerte de succès
        Swal.fire({
          title: "Emprunt ajouté !",
          text: res.data.message,
          icon: "success",
          confirmButtonColor: "#1e8449",
          confirmButtonText: "Voir la liste",
        }).then(() => {
          navigate("/emprunts"); // 🚀 Redirection vers la liste des emprunts
        });
      })
      .catch(() => {
        // ❌ Afficher une alerte d'erreur
        Swal.fire({
          title: "Erreur !",
          text: "Une erreur est survenue lors de l'ajout de l'emprunt.",
          icon: "error",
          confirmButtonColor: "#d33",
          confirmButtonText: "OK",
        });
      });
  };

  return (
    <div className="app-container">
      <h2>📦 Ajouter un emprunt</h2>
      <form onSubmit={handleSubmit}>
        {/* Sélection de l'utilisateur */}
        <select
          value={utilisateur}
          onChange={(e) => setUtilisateur(e.target.value)}
          required
        >
          <option value="">Sélectionner un utilisateur</option>
          {utilisateurs.map((u) => (
            <option key={u.id} value={u.id}>
              {u.nom} {u.prenom}
            </option>
          ))}
        </select>

        {/* Sélection du matériel */}
        <select
          value={materiel}
          onChange={(e) => setMateriel(e.target.value)}
          required
        >
          <option value="">Sélectionner un matériel</option>
          {materiels.map((m) => (
            <option key={m.id} value={m.id}>
              {m.nom}
            </option>
          ))}
        </select>

        <button type="submit">Valider l’emprunt</button>
      </form>
    </div>
  );
};

export default AjouterEmprunt;
