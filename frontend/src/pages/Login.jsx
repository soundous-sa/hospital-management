import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { login } from "../services/authService";

export default function Login() {
  const navigate = useNavigate();
  const [form, setForm] = useState({ email: "", password: "" });

  const handleChange = (e) => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const res = await login(form);
      localStorage.setItem("token", res.data.token);
      alert("Connexion réussie !");
      if (res.data.role === "patient") navigate("/dashboard-patient");
      else navigate("/"); // futur dashboard pour admin / medecin
    } catch (err) {
      console.error(err);
      alert("Email ou mot de passe incorrect");
    }
  };

  return (
    <div style={{ maxWidth: 400, margin: "50px auto", padding: 20, border: "1px solid #ddd", borderRadius: 10 }}>
      <h2>Connexion</h2>
      <form onSubmit={handleSubmit}>
        <div style={{ marginBottom: 10 }}>
          <label>Email</label>
          <input type="email" name="email" value={form.email} onChange={handleChange} style={{ width: "100%", padding: 8 }} />
        </div>
        <div style={{ marginBottom: 10 }}>
          <label>Mot de passe</label>
          <input type="password" name="password" value={form.password} onChange={handleChange} style={{ width: "100%", padding: 8 }} />
        </div>
        <button type="submit" style={{ backgroundColor: "var(--primary)", color: "#fff", padding: "10px 20px", border: "none", borderRadius: 5 }}>
          Se connecter
        </button>
      </form>
    </div>
  );
}
