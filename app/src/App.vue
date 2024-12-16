<script setup lang="ts">
import Sidebar from '@/components/Sidebar.vue';
</script>

<template>
  <div class="d-flex">
    <Sidebar />
    <div class="flex-grow-1 p-4">
      <router-view />
    </div>
  </div>
</template>
