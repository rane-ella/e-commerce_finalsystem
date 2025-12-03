import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost:8000/api', // Adjust if backend port differs
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true, // Important for Sanctum cookies if using SPA auth, but token auth usually sends Authorization header
});

// Add interceptor to attach token
api.interceptors.request.use((config) => {
    const token = typeof window !== 'undefined' ? localStorage.getItem('auth_token') : null;
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
