<template>
  <div class="main">
    <img alt="train" :src="TrainImage" class="img-cover"/>
    <div class="overlay"></div>
    <div class="block-left">
      <section class="section-form" v-if="!showItinerary">
        <h1>MOB</h1>
        <div class="header-form">
          <h2>Itinéraire</h2>
          <div class="inversion-block" @click="inversion">
            <InversionSvg  class="inversion"/>
            <span>Inverser <br>l'itinéraire</span>
          </div>

        </div>
        <form class="form-it">
          <InputAutocomplete
              label="Départ"
              id="start"
              v-model="start"
              :stations="allStations"
          />
          <InputAutocomplete
              label="Arrivée"
              id="end"
              v-model="end"
              :stations="allStations"
          />
          <span  v-if="errorMessage" class="error-form">{{ errorMessage }}</span>
          <input type="button" value="Rechercher" id="submit-it" @click="searchItinerary" />
        </form>
      </section>
      <section class="section-iti" v-else>
        <div class="iti-header">
          <ArrowSvg class="back-arrow" @click="backToSearch"/>
          <h2>Itinéraire trouvé</h2>
        </div>

        <Itinerary
            :total-distance="routeData.totalDistance"
            :segments="routeData.segments"
        />
      </section>
    </div>
  </div>

</template>

<script setup lang="ts">
import TrainImage from '@assets/images/img.png';
import InversionSvg from '@assets/svgs/double_direction_arrows.svg';
import { ApiService } from '@/services/Api.ts';
import InputAutocomplete from "@/components/InputSearch.vue";
import Itinerary from "@/components/Itinerary.vue";
import ArrowSvg from '@assets/svgs/arrow_left.svg';
import {computed, ref} from "vue";
import { useStationStore, type Station } from '@/stores/useStation.ts';



const stationStore = useStationStore();

const errorMessage = ref("");
const start = ref<string>("");
const end = ref<string>("");
const lastStart = ref<string>("");
const lastEnd = ref<string>("");
const allStations = computed(()=> stationStore.stations);
const showItinerary = ref(false);
const routeData = ref({
  segments: [],
  totalDistance: 0
});

function inversion(){
  const tmp = start.value;
  start.value = end.value
  end.value = tmp
}

async function searchItinerary(){
  errorMessage.value = "";

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
}
function backToSearch() {
  showItinerary.value = false;
}

</script>



<style scoped>
.inversion-block{
  display: flex;
  align-items: center;
  gap: 5px;
  cursor: pointer;
  font-size: 18px;
}
.inversion{
  color: white;
}
.header-form{
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: space-between;
}
div.main {
  position: relative;
  width: 100%;
  height: 100vh;
  overflow: hidden;
}

#submit-it{
  background-color: white;
  color: #2A4463;
  margin-top: 10px;
  font-size: 25px;
  height: 55px;
  cursor: pointer;
  border-radius: 8px;
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

section.section-form{
  color: white;
  display: flex;
  flex-direction: column;
  margin: 50px 0 0 100px;
  width: 450px;
}
section.section-form img{
 width: 200px;
}

form.form-it{
  display: flex;
  flex-direction: column;
  width: 450px;
  gap: 20px;
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

.error-form {
  display: flex;
  padding-left: 15px;
  border-radius: 8px;
  align-items: center;
  color: red;
  background-color: white;
  font-size: 18px;
  min-height: 50px;
}

.block-left{
  height: 100%;
  width: 450px;
}


.section-iti{
  margin: 20px 0 0 100px;
  width: 450px;
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

</style>