<template>
  <div class="main">
    <img alt="train" :src="TrainImage" class="img-cover"/>
    <div class="overlay"></div>
    <div class="block-left">
      <Transition name="slide" mode="out-in">
        <SearchForm
            v-if="!showItinerary"
            :stations="allStations"
            :loading="loadingSearch"
            :error-message="errorMessage"
            v-model:start="start"
            v-model:end="end"
            @search="searchItinerary"
            @invert="inversion"
            key="search"
        />
        <section class="section-iti" v-else key="itinerary">
          <ItineraryHeader @back="backToSearch" />
          <Itinerary :total-distance="routeData.distanceKm" :segments="routeData.segments" />
        </section>
      </Transition>

    </div>
  </div>

</template>

<script setup lang="ts">
import TrainImage from '@assets/images/img.png';
import { ApiService } from '@/services/Api.ts';
import Itinerary from "@/components/itinerary/Itinerary.vue";
import {computed, ref} from "vue";
import { useStationStore, type Station } from '@/stores/useStation.ts';
import ItineraryHeader from "@/components/itinerary/ItineraryHeader.vue";
import SearchForm from '@/components/itinerary/IteneraryForm.vue'

const stationStore = useStationStore();

const errorMessage = ref("");
const start = ref<string>("");
const end = ref<string>("");
const lastStart = ref<string>("");
const lastEnd = ref<string>("");
const allStations = computed(()=> stationStore.stations);
const showItinerary = ref(false);
const loadingSearch = ref(false);
const routeData = ref({
  segments: [],
  distanceKm: 0
});

function inversion(){
  const tmp = start.value;
  start.value = end.value
  end.value = tmp
}

async function searchItinerary(){
  if (loadingSearch.value) return;
  loadingSearch.value = true;
  errorMessage.value = "";

  try {
    if (!start.value.trim() || !end.value.trim()) {
      errorMessage.value = "Entrez votre départ et votre destination.";
      return;
    }

    const startStation :any = allStations.value.find( ( s  : Station ) => s.long_name.toLowerCase() === start.value.toLowerCase() )
    const endStation :any = allStations.value.find(( s  : Station ) => s.long_name.toLowerCase() === end.value.toLowerCase() )

    if (!startStation || !endStation) {
      errorMessage.value = "Veuillez sélectionner une station valide.";
      return;
    }

    if (startStation.id === endStation.id) {
      errorMessage.value =
          "Veuillez sélectionner un départ et une arrivée différents.";
      return;
    }

    if (lastStart.value === startStation.short_name && lastEnd.value === endStation.short_name) {
      showItinerary.value = true;
      return;
    }

    lastStart.value = startStation.short_name;
    lastEnd.value = endStation.short_name;

    const { data } = await ApiService.getRoute( startStation.short_name, endStation.short_name);
    routeData.value = data;
    showItinerary.value = true;
  }catch (e) {
    console.error(e);
  } finally {
    loadingSearch.value = false;
  }

}
function backToSearch() {
  showItinerary.value = false;
}

</script>



<style scoped>

div.main {
  position: relative;
  width: 100%;
  height: 100vh ;
  overflow: hidden;
  padding-top: 100px;
}

h1{
  font-size: 80px;
  margin: 20px 0;
}

h2{
  font-size: 32px;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: linear-gradient(90deg, #2A4463, #2A4463 40%, rgba(42, 68, 99, 0));
  z-index: -98;
}


img.img-cover{
  position: absolute;
  width: 100%;
  height: 100%;
  object-fit: cover;
  top: 0;
  left: 0;
  z-index: -99;
}

.block-left{
  height: 100%;
  width: 450px;
}

.section-iti{
  margin: 20px 0 0 100px;
  width: 450px;
}


.slide-leave-active {
  transition: transform 0.6s ease-in-out, opacity 0.6s ease-in-out;
}
.slide-leave-to {
  transform: translateY(-100vh);
  opacity: 0;
}

.slide-enter-active {
  transition: transform 0.6s ease-in-out, opacity 0.6s ease-in-out ;
}
.slide-enter-from {
  transform: translateY(100vh);
  opacity: 0;
}
.slide-enter-to {
  transform: translateY(0);
  opacity: 1;
}

</style>