import axios from "axios";

const API_URL = "http://localhost:8000/api"; // ton backend Laravel

const api = axios.create({
  baseURL: API_URL,
});

// Ajouter le token automatiquement si présent
api.interceptors.request.use((config) => {
  const token = localStorage.getItem("token");
  if (token) config.headers.Authorization = `Bearer ${token}`;
  return config;
});

export default api;
