<template>
  <div class="route-container" v-if="segments.length">
    <div class="route-summary">
      <span>Distance totale :</span>
      <strong>{{ totalDistance }} km</strong>
    </div>

    <div class="route-steps">
      <ul>
        <li v-for="(segment, index) in segments" :key="index">
          <div class="station-info">
            <span class="station">{{ segment.from }}</span>
            <span class="arrow">→</span>
            <span class="station">{{ segment.to }}</span>
          </div>

          <div class="segment-distance">
            {{ segment.distance }} km
          </div>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">

interface Segment {
  from: string;
  to: string;
  distance: number;
}

defineProps<{
  segments: Segment[];
  totalDistance: number;
}>();
</script>

<style scoped>
.route-container {
  width: 450px;
  background: #2A4463cc;
  backdrop-filter: blur(8px);
  border-radius: 12px;
  color: white;
  font-family: Arial, sans-serif;
}

.route-summary {
  display: flex;
  justify-content: space-between;
  background: rgba(255,255,255,0.1);
  padding: 12px 15px;
  border-radius: 8px;
  margin-bottom: 20px;
  font-size: 18px;
}

.route-summary strong {
  font-size: 20px;
  color: #7cc8ff;
}

.route-steps ul {
  padding: 0;
  margin: 0;
  list-style: none;
}

.route-steps li {
  background: rgba(255,255,255,0.08);
  padding: 12px 15px;
  border-radius: 8px;
  margin-bottom: 8px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.station-info {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 18px;
  font-weight: 500;
}

.station {
  padding: 4px 8px;
  background: rgba(255,255,255,0.2);
  border-radius: 5px;
  min-width: 70px;
  text-align: center;
}

.arrow {
  opacity: 0.7;
}

.segment-distance {
  font-size: 16px;
  opacity: 0.8;
}

.route-steps {
  max-height: calc( 100vh - 200px);
  overflow-y: auto;
  padding-right: 5px;
}

.route-steps::-webkit-scrollbar {
  width: 8px;
}

.route-steps::-webkit-scrollbar-track {
  background: rgba(255,255,255,0.1);
  border-radius: 10px;
}

.route-steps::-webkit-scrollbar-thumb {
  background: rgba(255,255,255,0.4);
  border-radius: 10px;
}

.route-steps::-webkit-scrollbar-thumb:hover {
  background: rgba(255,255,255,0.6);
}
</style>
