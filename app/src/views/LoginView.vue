<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '@/stores/authStore';

const router = useRouter();
const authStore = useAuthStore();

const email = ref('');
const password = ref('');
const errorMessage = ref('');

const login = async () => {
  try {
    await authStore.login(email.value, password.value);
    localStorage.setItem('authToken', authStore.token);
    router.push('/');
  } catch (error) {
    errorMessage.value = (error instanceof Error) ? error.message : 'Erro desconhecido.';
  }
};
</script>

<template>
  <div>
    <h3>Login</h3>
    <form @submit.prevent="login">
      <div class="mb-3">
        <label for="email" class="form-label">Email:</label>
        <input v-model="email" id="email" type="email" class="form-control" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Senha:</label>
        <input v-model="password" id="password" type="password" class="form-control" required />
      </div>
      <button type="submit" class="btn btn-primary">Entrar</button>
      <p v-if="errorMessage" class="text-danger">{{ errorMessage }}</p>
    </form>
  </div>
</template>

<style scoped>
form {
  max-width: 400px;
  margin: auto;
}
</style>
