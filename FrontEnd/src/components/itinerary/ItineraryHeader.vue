<template>
  <div class="iti-header">
    <ArrowSvg class="back-arrow" @click="$emit('back')" />
    <h2>Itinéraire trouvé</h2>
  </div>
</template>

<script setup lang="ts">
import ArrowSvg from '@assets/svgs/arrow_left.svg';
defineEmits<{ (e: 'back'): void; }>();
</script>

<style scoped>

.iti-header {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 15px;
  border-bottom: 2px solid rgba(255,255,255,0.3);
  width: 100%;
}

.iti-header  h2 {
  color: white;
  margin: 5px;
  font-size: 28px;
}

.back-arrow {
  cursor: pointer;
  margin-right: 10px;
  user-select: none;
  transition: 0.2s;
  color: white;
}

.back-arrow:hover {
  opacity: 0.7;
  transform: translateX(-3px);
}
</style>
