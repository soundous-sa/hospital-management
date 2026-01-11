import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { registerPatient } from "../services/authService";

export default function Register() {
  const navigate = useNavigate();
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    cin: "",
    date_naissance: "",
    sexe: "M",
    telephone: "",
    adresse: "",
    groupe_sanguin: "",
    allergies: "",
    maladies_chroniques: "",
    contact_urgence: ""
  });

  const handleChange = (e) => {
    setForm({ ...form, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      // Mapper les noms de champs du formulaire aux noms attendus par l'API
      const payload = {
        name: form.name,
        email: form.email,
        password: form.password,
        cin: form.cin,
        date_naissance: form.date_naissance,
        sexe: form.sexe,
        telephone: form.telephone,
        adresse: form.adresse,
        contact_urgence: form.contact_urgence,
        blood_type: form.groupe_sanguin,
        chronic_diseases: form.maladies_chroniques,
        allergies: form.allergies
      };
      
      const res = await registerPatient(payload);
      localStorage.setItem("token", res.data.token);
      window.dispatchEvent(new Event("storage"));
      alert("Inscription réussie !");
      navigate("/dashboard-patient");
    } catch (err) {
      console.error(err);
      alert(err.response?.data?.error || err.response?.data?.message || "Erreur inscription");
    }
  };

  return (
    <div style={{ maxWidth: 600, margin: "50px auto", padding: 20, border: "1px solid #ddd", borderRadius: 10 }}>
      <h2>Inscription Patient</h2>
      <form onSubmit={handleSubmit}>
        {Object.keys(form).map((key) => (
          <div key={key} style={{ marginBottom: 10 }}>
            <label>{key}</label>
            <input
              type={key === "password" ? "password" : "text"}
              name={key}
              value={form[key]}
              onChange={handleChange}
              style={{ width: "100%", padding: 8 }}
            />
          </div>
        ))}
        <button type="submit" style={{ backgroundColor: "var(--primary)", color: "#fff", padding: "10px 20px", border: "none", borderRadius: 5 }}>
          S'inscrire
        </button>
      </form>
    </div>
  );
}
