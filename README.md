
🎒 Système de Gestion des Emprunts de Matériel

Ce projet a été réalisé dans le cadre de ma formation DWWM (Développeur Web et Web Mobile).  
Il s'agit d'une application web permettant de gérer les emprunts et retours de matériel au sein d'une structure (école, entreprise, association…).

📌 Fonctionnalités principales

- Ajout d’un emprunt via un formulaire
- Liste des emprunts en cours avec filtres (utilisateur, date, matériel, statut…)
- Retour de matériel avec mise à jour du statut
- Historique des emprunts retournés
- Gestion sécurisée des données (requêtes préparées, validation côté front)

🛠️ Technologies utilisées

- Front-end : ReactJS (JSX, SCSS)
- Back-end : PHP (orienté fonctionnel), MySQL
- Base de données : modélisation avec MCD/MPD, requêtes via PDO

📂 Arborescence recommandée

/front          → Code React (composants, pages, styles)
/back           → Fichiers PHP (API, fonctions, accès DB)
/sql            → gestion_emprunts1.sql (export de la BDD)
/README.md      → Ce fichier


💾 Installation (en local)

1. Clonez le dépôt :

   git clone https://github.com/Wiwii97x/gestion-emprunts-materiel.git

2. Importez la base de données :
   - Ouvrir phpMyAdmin ou un outil MySQL
   - Importer le fichier `gestion_emprunts1.sql`

3. Lancez le front :
   cd front
   npm install
   npm start
  
4. Placez les fichiers PHP dans un dossier `/back` sur votre serveur local


🔐 Sécurité

- Requêtes SQL sécurisées avec PDO
- Nettoyage des entrées utilisateurs (`strip_tags`, `htmlentities`)
- Vérifications côté client pour éviter les champs vides ou invalides


📸 Aperçu

*(Tu peux ajouter ici une ou deux captures d’écran si tu veux)*


👨‍💻 Auteur

- Willem Concy – Développeur Web Junior  
- Formation DWWM – 2024-2025  
- [GitHub](https://github.com/Wiwii97x)
