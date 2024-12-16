<script setup lang="ts">
import { ref, watch, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '@/axios';
// @ts-ignore
import JsonViewer from 'vue-json-viewer';

const route = useRoute();

const selectedMethod = ref('GET');
const endpointUrl = ref('');
const requestBody = ref('{}');
const responseBody = ref<string | null>(null);
const errorMessage = ref<string | null>(null);
const endpointInput = ref<HTMLElement | null>(null);

onMounted(() => {
  if (endpointInput.value) {
    endpointInput.value.focus();
  }
});

watch(
  () => route.query,
  () => {
    selectedMethod.value = (route.query.method as string) || 'GET';
    endpointUrl.value = (route.query.url as string) || '';
    requestBody.value = (route.query.payload as string) || '{}';
    responseBody.value = null;
    errorMessage.value = null;

    if (endpointUrl.value && endpointInput.value) {
      endpointInput.value.focus();
    }
  },
  { immediate: true }
);

const sendRequest = async () => {
  try {
    let response;
    errorMessage.value = null;

    switch (selectedMethod.value) {
      case 'GET':
        response = await api.get(endpointUrl.value);
        break;
      case 'POST':
        response = await api.post(endpointUrl.value, JSON.parse(requestBody.value));
        break;
      case 'PUT':
        response = await api.put(endpointUrl.value, JSON.parse(requestBody.value));
        break;
      case 'DELETE':
        response = await api.delete(endpointUrl.value);
        break;
    }

    responseBody.value = (response && response.data)
      ? JSON.parse(JSON.stringify(response.data, null, 2))
      : 'Nenhuma resposta recebida.';

  } catch (error: any) {
    errorMessage.value = error.response?.data || error.message;
    responseBody.value = null;
  }
};
</script>

<template>
  <div>
    <h3>Testar Endpoints</h3>

    <!-- Barra de Método e Endpoint -->
    <div class="d-flex mb-3">
      <select v-model="selectedMethod" class="form-select me-2" style="max-width: 120px">
        <option value="GET">GET</option>
        <option value="POST">POST</option>
        <option value="PUT">PUT</option>
        <option value="DELETE">DELETE</option>
      </select>
      <div class="input-group">
        <span class="input-group-text" id="basic-addon3">http://localhost/api</span>
        <input
          ref="endpointInput"
          @keyup.enter="sendRequest"
          type="text"
          class="form-control"
          id="basic-url"
          v-model="endpointUrl"
          placeholder="Digite a URL do endpoint..."
          aria-describedby="basic-addon3 basic-addon4"
        />
      </div>
    </div>
    <div class="d-flex mb-4">
      <button @click="sendRequest" class="btn btn-primary" style="width: 150px; max-width: 120px;">Enviar</button>
    </div>


    <!-- Payload -->
    <div v-if="selectedMethod === 'POST' || selectedMethod === 'PUT'" class="mb-3">
      <h5>Payload da Requisição</h5>
      <textarea v-model="requestBody" rows="6" class="form-control" placeholder="{ }"></textarea>
    </div>

    <!-- Resposta -->
    <div>
      <h5>Resposta</h5>
      <pre v-if="responseBody" class="bg-light p-3 border">
        <code><json-viewer :value=responseBody :expand-depth="3" :preview-mode="false"/></code>
      </pre>
      <pre v-if="errorMessage" class="text-danger p-3 border error">
        <code><json-viewer :value=errorMessage :expand-depth="3" :preview-mode="false"/></code>
      </pre>
    </div>
  </div>
</template>

<style scoped lang="scss">
@import '../assets/json-viewer.css';

textarea {
  resize: none;
}

pre {
  max-height: 50vh;
  padding: 0!important;

  code {
    padding: 0!important;
  }
}

</style>
