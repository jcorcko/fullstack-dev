import axios from "axios";

const APP_URL = "http://localhost:8000/api";

export const register = (data: any) => axios.post(`${APP_URL}/register`, data);
export const login = (data: any) => axios.post(`${APP_URL}/login`, data);
export const profile = (data: any, token: string) => axios.post(`${APP_URL}/profile`, data, {
    headers: {
        Authorization: `Bearer ${token}`
    }
});
export const logout = (data: any, token: string) => axios.post(`${APP_URL}/logout`, data, {
    headers: {
        Authorization: `Bearer ${token}`
    }
});