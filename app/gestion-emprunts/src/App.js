import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import Navigation from "./components/Navigation";
import Home from "./pages/Home";
import AjouterEmprunt from "./pages/AjouterEmprunt";
import Emprunts from "./pages/Emprunts";

const App = () => {
  return (
    <Router>
      <Navigation />
      <Routes>
        <Route path="/" element={<Home />} />
        <Route path="/ajouter-emprunt" element={<AjouterEmprunt />} />
        <Route path="/emprunts" element={<Emprunts />} />
      </Routes>
    </Router>
  );
};

export default App;
