<script setup lang="ts">
import { useAuthStore } from '@/stores/authStore';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const sections = [
  {
    title: 'Auth',
    endpoints: [
      { method: 'GET', name: 'me', url: '/me' },
      { method: 'POST', name: 'login', url: '/login', payload: { email: 'johndoe@mail.com', password: '@12345678' } },
      { method: 'POST', name: 'refresh', url: '/refresh', payload: {} },
      { method: 'POST', name: 'logout', url: '/logout', payload: {} },
    ],
  },
  {
    title: 'Campaigns',
    endpoints: [
      { method: 'GET', name: 'All Campaigns', url: '/campaigns' },
      { method: 'GET', name: 'View Campaign', url: '/campaigns/1' },
      { method: 'POST', name: 'Create Campaign', url: '/campaigns', payload: {
          name: "Campaign Name",
          budget: 75000.00,
          desc: "Description",
          init_date: "2025-01-10",
          end_date: "2025-03-10",
          influencers: [1, 2, 3]
      } },
      { method: 'PUT', name: 'Edit Campaign', url: '/campaigns/1', payload: {
        name: "Summer Campaign 3",
        budget: 75000.00,
        desc: "Promoting summer collection.",
        init_date: "2025-01-10",
        end_date: "2025-03-10",
        influencers: [2, 3]
      } },
      { method: 'DELETE', name: 'Delete Campaign', url: '/campaigns/1', payload: { } },
    ],
  },
  {
    title: 'Influencers',
    endpoints: [
      { method: 'GET', name: 'All Influencers', url: '/influencers' },
      { method: 'GET', name: 'View Influencer', url: '/influencers/1' },
      { method: 'POST', name: 'Create Influencer', url: '/influencers', payload: {
        name: "Influencer Name",
        ig_user: "@ig_influencer",
        followers: 99000,
        category: "Tech",
        campaigns: [1, 3]
      } },
      { method: 'PUT', name: 'Edit Influencer', url: '/influencers/1', payload: {
        name: "Influencer Name",
        ig_user: "@ig_influencer_2",
        followers: 99000,
        category: "Tech",
        campaigns: [1, 2, 3]
      } },
      { method: 'DELETE', name: 'Delete Influencer', url: '/influencers/1', payload: { } },
    ],
  },
];

const openEndpoint = (method: string, url: string, payload: any = null) => {
  router.push({
    path: '/',
    query: { method, url, payload: payload ? JSON.stringify(payload, null, 2) : '' },
  });
};
</script>

<template>
  <div class="sidebar bg-dark text-light min-vh-100 p-3 d-flex flex-column">
    <div v-for="section in sections" :key="section.title" class="mb-4">
      <h5>{{ section.title }}</h5>
      <ul class="list-unstyled">
        <li
          v-for="endpoint in section.endpoints"
          :key="endpoint.name"
          class="pointer mb-2"
          @click="openEndpoint(endpoint.method, endpoint.url, endpoint.payload)"
        >
          <span :class="'badge me-2 ' + methodBadgeClass(endpoint.method)">
            {{ endpoint.method }}
          </span>
          {{ endpoint.name }}
        </li>
      </ul>
    </div>
    <!-- Botão de Logout -->
    <button @click="authStore.logout" class="btn btn-danger w-100 mt-auto">
      Logout
    </button>
  </div>
</template>

<script lang="ts">
const methodBadgeClass = (method: string) => {
  switch (method) {
    case 'GET':
      return 'bg-success';
    case 'POST':
      return 'bg-warning text-dark';
    case 'PUT':
      return 'bg-primary';
    case 'DELETE':
      return 'bg-danger';
    default:
      return 'bg-secondary';
  }
};
</script>

<style scoped lang="scss">
.pointer {
  cursor: pointer;
  transition: color 0.2s;
  
  &:hover {
    color: #adb5bd;
  }
}
.badge {
  width: 64px;
}
.sidebar {
  min-width: 250px;
}
</style>
