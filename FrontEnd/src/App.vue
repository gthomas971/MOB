<template>
  <div id="app">
    <div v-if="stationStore.loading" class="loader">
      <TrainSvg class="train"/>
      <span>Chargement...</span>
      <TrainSvg class="train"/>
    </div>
    <div v-else>
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
import { useStationStore } from '@/stores/useStation.ts';
import TrainSvg from '@assets/svgs/train.svg'

const stationStore = useStationStore();
</script >

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
}

.train {
  width: 120px;
  animation: moveTrain 2s linear infinite;
  margin-bottom: 20px;
}

/* Animation horizontale simple */
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
