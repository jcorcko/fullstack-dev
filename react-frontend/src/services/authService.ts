import axios from "axios";

const APP_URL = "http://localhost:8000/api";

export const register = (data: any) => axios.post(`${APP_URL}/register`, data);