import React, { useEffect, useState, useMemo } from "react";
import Api from "../services/Api";
import Swal from "sweetalert2";
import ReactPaginate from "react-paginate";
import {
  useReactTable,
  getCoreRowModel,
  getFilteredRowModel,
  getPaginationRowModel,
  flexRender,
} from "@tanstack/react-table";

const Emprunts = () => {
  const [emprunts, setEmprunts] = useState([]);
  const [searchUtilisateur, setSearchUtilisateur] = useState("");
  const [searchMateriel, setSearchMateriel] = useState("");
  const [searchStatut, setSearchStatut] = useState("");
  const [searchDate, setSearchDate] = useState("");
  const [pageSize, setPageSize] = useState(10);
  const [pageIndex, setPageIndex] = useState(0);

  useEffect(() => {
    Api.get("/emprunts/getAll.php")
      .then((res) => setEmprunts(res.data))
      .catch((err) => console.error("❌ Erreur chargement emprunts :", err));
  }, []);

  // 📌 Filtrage des emprunts
  const filteredEmprunts = useMemo(() => {
    return emprunts.filter((emprunt) => {
      const matchUtilisateur = searchUtilisateur
        ? emprunt.utilisateur
            ?.toLowerCase()
            .includes(searchUtilisateur.toLowerCase())
        : true;

      const matchMateriel = searchMateriel
        ? emprunt.materiel?.toLowerCase().includes(searchMateriel.toLowerCase())
        : true;

      const matchStatut = searchStatut ? emprunt.statut === searchStatut : true;

      const matchDate = searchDate
        ? emprunt.date_emprunt.startsWith(
            searchDate.split("-").reverse().join("-")
          )
        : true;

      return matchUtilisateur && matchMateriel && matchStatut && matchDate;
    });
  }, [emprunts, searchUtilisateur, searchMateriel, searchStatut, searchDate]);

  // 📌 Définition des colonnes du tableau
  const columns = useMemo(
    () => [
      { accessorKey: "id", header: "ID" },
      { accessorKey: "utilisateur", header: "Utilisateur" },
      { accessorKey: "materiel", header: "Matériel" },
      { accessorKey: "date_emprunt", header: "Date & Heure d'emprunt" },
      { accessorKey: "date_retour_effectif", header: "Date & Heure de retour" },
      {
        header: "Statut",
        accessorKey: "statut",
        cell: ({ row }) => (
          <span
            className={
              row.original.statut === "en cours"
                ? "statut-en-cours"
                : "statut-rendu"
            }
          >
            {row.original.statut === "en cours" ? "🔴 En cours" : "✅ Rendu"}
          </span>
        ),
      },
      {
        header: "Action",
        cell: ({ row }) =>
          row.original.statut === "en cours" ? (
            <button
              onClick={() => retournerEmprunt(row.original.id)}
              className="btn-retourner"
            >
              🔄 Retourner
            </button>
          ) : (
            "✔️ Déjà rendu"
          ),
      },
    ],
    []
  );

  // 📌 Création de la table avec pagination
  const table = useReactTable({
    data: filteredEmprunts,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    state: { pagination: { pageIndex, pageSize } },
    onPaginationChange: (updater) => {
      const newPagination = updater({ pageIndex, pageSize });
      setPageIndex(newPagination.pageIndex);
      setPageSize(newPagination.pageSize);
    },
  });

  const retournerEmprunt = (id) => {
    Swal.fire({
      title: "Êtes-vous sûr ?",
      text: "Cette action va marquer l'emprunt comme retourné.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#d33",
      confirmButtonText: "Oui, retourner !",
      cancelButtonText: "Annuler",
    }).then((result) => {
      if (result.isConfirmed) {
        Api.post("/emprunts/return.php", { id })
          .then(() => {
            Swal.fire("✅ Succès !", "L'emprunt a été retourné.", "success");

            setEmprunts((prevEmprunts) =>
              prevEmprunts.map((emprunt) =>
                emprunt.id === id
                  ? {
                      ...emprunt,
                      statut: "rendu",
                      date_retour_effectif: new Date().toLocaleString("fr-FR"),
                    }
                  : emprunt
              )
            );
          })
          .catch(() => {
            Swal.fire(
              "❌ Erreur",
              "Impossible de retourner l'emprunt.",
              "error"
            );
          });
      }
    });
  };

  return (
    <div className="app-container">
      <h2>📋 Liste des emprunts</h2>

      {/* 🔍 Filtres */}
      <div className="filter-container">
        <input
          type="text"
          placeholder="🔎 Filtrer par utilisateur"
          value={searchUtilisateur}
          onChange={(e) => setSearchUtilisateur(e.target.value)}
          className="search-bar"
        />
        <input
          type="text"
          placeholder="🔎 Filtrer par matériel"
          value={searchMateriel}
          onChange={(e) => setSearchMateriel(e.target.value)}
          className="search-bar"
        />
        <input
          type="date"
          value={searchDate}
          onChange={(e) => setSearchDate(e.target.value)}
          className="date-filter"
        />
        <select
          value={searchStatut}
          onChange={(e) => setSearchStatut(e.target.value)}
          className="filter-select"
        >
          <option value="">Tous les statuts</option>
          <option value="en cours">En cours</option>
          <option value="rendu">Rendu</option>
        </select>
      </div>

      {/* 📊 Tableau */}
      <table className="table">
        <thead>
          {table.getHeaderGroups().map((headerGroup) => (
            <tr key={headerGroup.id}>
              {headerGroup.headers.map((column) => (
                <th key={column.id}>
                  {flexRender(
                    column.column.columnDef.header,
                    column.getContext()
                  )}
                </th>
              ))}
            </tr>
          ))}
        </thead>
        <tbody>
          {table.getRowModel().rows.map((row) => (
            <tr key={row.id}>
              {row.getVisibleCells().map((cell) => (
                <td key={cell.id}>
                  {flexRender(cell.column.columnDef.cell, cell.getContext())}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>

      {/* 🔄 Pagination et choix du nombre d'éléments par page */}
      <div className="pagination-container">
        <ReactPaginate
          previousLabel={"← Précédent"}
          nextLabel={"Suivant →"}
          pageCount={table.getPageCount()} // Nombre total de pages
          onPageChange={({ selected }) => table.setPageIndex(selected)}
          containerClassName={"pagination"}
          previousLinkClassName={"prev-page"}
          nextLinkClassName={"next-page"}
          disabledClassName={"pagination-disabled"}
          activeClassName={"selected"} // Ajout de la classe sélectionnée
        />

        {/* 🔽 Sélecteur du nombre d'éléments affichés par page */}
        <select
          value={table.getState().pagination.pageSize}
          onChange={(e) => table.setPageSize(Number(e.target.value))}
        >
          {[5, 10, 20, 50].map((size) => (
            <option key={size} value={size}>
              {size}
            </option>
          ))}
        </select>
      </div>
    </div>
  );
};

export default Emprunts;
