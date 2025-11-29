<template>
  <button
      type="button"
      :disabled="loading"
      class="btn-search"
      @click="$emit('click')"
  >
    <span class="text"><slot /></span>

    <span v-if="loading" class="loader"></span>
  </button>
</template>

<script setup lang="ts">
defineProps<{ loading: boolean; }>();

defineEmits<{ (e: "click"): void; }>();
</script>

<style scoped>
.btn-search {
  background-color: white;
  color: #2A4463;
  font-size: 25px;
  height: 55px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  padding: 0 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
}

.btn-search:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}


.loader {
  right: 12px;
  width: 18px;
  height: 18px;
  border: 3px solid rgba(42, 68, 99, 0.3);
  border-top-color: #2A4463;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

.text {
  user-select: none;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}
</style>
