import api from "./api";

export const registerPatient = async (data) => {
  return api.post("/register", data);
};

export const login = async (data) => {
  return api.post("/login", data);
};

