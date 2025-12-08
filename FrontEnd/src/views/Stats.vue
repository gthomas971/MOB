<template>
  <div class="page">
    <h1 class="title">Statistiques des distances en km</h1>

    <div class="filters">
      <div class="chart-date">
        <input type="date" v-model="from" />
        <input type="date" v-model="to" />
        <button @click="loadData" class="btn-load">Charger</button>
      </div>


      <div class="chart-switch">
        <button :class="{ active: chartType === 'bar' }" @click="chartType = 'bar'">
          <BarSvg />
        </button>

        <button :class="{ active: chartType === 'pie' }" @click="chartType = 'pie'">
          <PieSvg />
        </button>
      </div>


    </div>

    <div class="chart-container">
      <Loader
          v-show="loading"
          :message="'Chargement...'"
          :left-svg="BarSvg"
          :right-svg="PieSvg"
          :little="true"
      />
     <canvas ref="mainChart"></canvas>
    </div>
  </div>
</template>

<script setup lang="ts">


import { ref, onMounted, watch } from "vue";
import { Chart } from "chart.js/auto";
import { ApiService } from '@/services/Api.ts';
import PieSvg from '@assets/svgs/pie.svg';
import BarSvg from '@assets/svgs/bar.svg';
import Loader from "@/components/common/Loader.vue";

const from = ref("");
const to = ref("");
const chartType = ref<'bar'|'pie'>("bar");
const loading = ref(true);

const distances = ref([]);

const mainChart = ref(null);
let chartInstance : any = null;

async function loadData() {
  chartInstance?.destroy();
  loading.value = true;
  try {
    const { data } = await ApiService.getStats(from.value, to.value);
    distances.value = data.items;
    renderChart();
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
  }
}

function generateColors(count :number) {

  const baseHue = 210;
  const baseSat = 45;
  const baseLight = 40;

  return Array.from({ length: count }, (_, i) => {
    const hue = baseHue + (i - count / 2) * 6;
    const sat = baseSat + (i % 3) * 5;
    const light = baseLight + ((i * 12) % 25);

    return `hsl(${hue}, ${sat}%, ${light}%)`;
  });
}



function renderChart() {

  const labels = distances.value.map( ( i : any) => i.analyticCode);
  const values = distances.value.map(( i : any) => i.totalDistanceKm);

  chartInstance?.destroy();

  const colors = generateColors(values.length);

  if (!mainChart.value) return;

  chartInstance = new Chart(mainChart.value, {
    type: chartType.value ,
    data: {
      labels,
      datasets: [
        {
          label: "Distance totale (km)",
          data: values,
          backgroundColor: chartType.value === "pie" ? colors : '#276B8F',
          borderColor: "#333",
        },
      ],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
    },
  });
}

watch(chartType, renderChart);

onMounted( async ()=>{
  const today = new Date();
  const lastYear = new Date();
  lastYear.setFullYear(today.getFullYear() - 1);

  const formatDate = (date : Date) => date.toISOString().slice(0, 10);

  from.value = formatDate(lastYear);
  to.value = formatDate(today);
  await loadData()
});
</script>


<style scoped>
.page {
  padding: 100px 30px 30px 30px;
  max-width: 900px;
  margin: auto;
}

.title {
  margin-bottom: 20px;
  font-size: 28px;
  font-weight: bold;
}

.filters {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 25px;
}

.chart-container {
  margin-top: 40px;
  width: 100%;
  height: 400px;
}

.chart-date{
  display: flex;
  gap: 20px;
  height: 40px;
}

.chart-date input{
  padding: 8px 12px;
  border-radius: 8px;
  border-color: #2A4463;
}

.btn-load{
  background-color: #2A4463;
  color: white;
  padding: 8px 12px;
  border-radius: 8px;
  cursor: pointer;
}

/*********************************************************/
.chart-switch {
  display: flex;
  gap: 10px;
  height: 40px;
}

.chart-switch button {
  padding: 8px 12px;
  border: 1px solid #ccc;
  background: #f7f7f7;
  border-radius: 8px;
  cursor: pointer;
  transition: 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}

.chart-switch button svg {
  fill: #2A4463;
  transition: 0.2s;
}

.chart-switch button.active {
  background: #2A4463;
  border-color: #2A4463;
}

.chart-switch button.active svg {
  fill: white;
}

.chart-switch button:hover {
  background: #e4e8ee;
}

</style>
