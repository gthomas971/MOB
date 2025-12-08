<template>
  <div id="app">
    <Loader
        v-if="stationStore.loading"
        :message="'Veuillez patienter...'"
        :left-svg="TrainSvg"
        :right-svg="TrainSvg"
        :little="false"
    />
    <div v-else>
      <Header />
      <main>
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useStationStore } from '@/stores/useStation.ts';
import TrainSvg from '@assets/svgs/train.svg'
import Loader from "@/components/common/Loader.vue";
import Header from "@/components/layout/Header.vue";

const stationStore = useStationStore();
</script >

<style scoped>

</style>
