import axios from "axios";

const Api = axios.create({
  baseURL: "http://localhost/projet_perso/api/routes", // ⚠️ Adapte l'URL selon ton projet
  headers: {
    "Content-Type": "application/json",
  },
});

export default Api;
