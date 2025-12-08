<template>
  <div :class="['loader', little && 'little']">
    <component :is="leftSvg" class="train" v-if="leftSvg"/>
    <span>{{ message }}</span>
    <component :is="rightSvg" class="train" v-if="rightSvg"/>
  </div>
</template>

<script setup lang="ts">

const props = defineProps<{
  message?: string;
  leftSvg?: any;
  rightSvg?: any;
  little?: boolean;
}>();

const message = props.message ?? "Chargement...";
</script>

<style scoped>
.loader {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 24px;
  background: #2A4463;
  opacity: 0.8;
  color: white;
  z-index: 9999;
  gap: 20px;
}

.loader.little{
  color: #2A4463;
  position: relative;
  background-color: transparent;
}
.train {
  width: 120px;
  animation: moveTrain 2s linear infinite;
}

@keyframes moveTrain {
  0%   { transform: translateX(-50px) rotate(0deg); }
  50%  { transform: translateX(50px) rotate(5deg); }
  100% { transform: translateX(-50px) rotate(0deg); }
}

.loader span {
  font-size: 20px;
  font-weight: 500;
  text-align: center;
}
</style>
