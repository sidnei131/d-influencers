import { defineStore } from 'pinia';
import api from '@/axios';
import router from '@/router';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    token: localStorage.getItem('authToken') || '',
    user: null as any | null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
  },
  actions: {
    // Login
    async login(email: string, password: string) {
      const response = await api.post('/login', { email, password });
      this.token = response.data.token;
      this.user = response.data.user;
      localStorage.setItem('authToken', this.token);
      api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
    },

    // Logout
    logout() {
      this.token = '';
      this.user = null;
      localStorage.removeItem('authToken');
      delete api.defaults.headers.common['Authorization'];
      router.push('/login');
    },

    // Atualizar Token
    async refreshToken() {
      const response = await api.post('/refresh', {
        refresh_token: this.refreshToken,
      });
      this.token = response.data.token;
      localStorage.setItem('authToken', this.token);
      api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`;
      return response.data.token;
    }, 
  },
});
