import axios from 'axios';
import { useAuthStore } from '@/stores/authStore';

const api = axios.create({
  baseURL: 'http://localhost/api',
});

// Interceptores de requisição e resposta
api.interceptors.request.use((config) => {
  const authStore = useAuthStore();

  if (config.url?.includes('/login')) {
    delete config.headers['Authorization'];
  } else if (authStore.token) {
    config.headers['Authorization'] = `Bearer ${authStore.token}`;
  }
  return config;
});

export default api;
