import React, { useEffect, useState } from "react";
import api from "../services/api";

export default function PatientDashboard() {
  const [profile, setProfile] = useState(null);

  useEffect(() => {
    api.get("/profile")
      .then((res) => setProfile(res.data))
      .catch((err) => console.error(err));
  }, []);

  if (!profile) return <p>Chargement...</p>;

  return (
    <div style={{ padding: 20 }}>
      <h2>Bienvenue {profile.name}</h2>
      <p>Email: {profile.email}</p>
      <p>Rôle: {profile.role}</p>
    </div>
  );
}
