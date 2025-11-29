<template>
  <section class="section-form">
    <h1>MOB</h1>
    <div class="header-form">
      <h2>Itinéraire</h2>
      <div class="inversion-block" @click="$emit('invert')">
        <InversionSvg class="inversion" />
        <span>Inverser <br />l'itinéraire</span>
      </div>
    </div>

    <form class="form-it" @submit.prevent="onSubmit">
      <InputAutocomplete
          label="Départ"
          id="start"
          v-model="localStart"
          :stations="stations"
      />
      <InputAutocomplete
          label="Arrivée"
          id="end"
          v-model="localEnd"
          :stations="stations"
      />

      <span v-if="errorMessage" class="error-form">{{ errorMessage }}</span>

      <LoaderButton :loading="loading" @click="onSubmit">
        Rechercher
      </LoaderButton>
    </form>
  </section>
</template>

<script setup lang="ts">
import { ref, watch, defineProps, defineEmits } from "vue";
import InputAutocomplete from "@/components/InputSearch.vue";
import LoaderButton from "@/components/LoaderButton.vue";
import InversionSvg from "@/assets/svgs/double_direction_arrows.svg";

import type { Station } from "@/stores/useStation.ts";

const props = defineProps<{
  stations: Station[];
  start: string;
  end: string;
  loading: boolean;
  errorMessage: string;
}>();

const emits = defineEmits<{
  (e: "update:start", value: string): void;
  (e: "update:end", value: string): void;
  (e: "search", start: string, end: string): void;
  (e: "invert"): void;
}>();

const localStart = ref(props.start);
const localEnd = ref(props.end);

watch(() => props.start, val => localStart.value = val);
watch(() => props.end, val => localEnd.value = val);

watch(localStart, (newVal) => {
  if (newVal) {
    emits("update:start", newVal);
  }
});

watch(localEnd, (newVal) => {
  if (newVal) {
    emits("update:end", newVal);
  }
});

function onSubmit() {
 emits("search", localStart.value, localEnd.value);
}

</script>

<style scoped>

section.section-form{
  color: white;
  display: flex;
  flex-direction: column;
  margin: 50px 0 0 100px;
  width: 450px;
}

.section-form {
  color: white;
  display: flex;
  flex-direction: column;
  max-width: 450px;
  margin: 50px 0 0 100px;
}

.header-form {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 20px;
}

.inversion-block {
  display: flex;
  align-items: center;
  gap: 5px;
  cursor: pointer;
  font-size: 18px;
}

.inversion {
  color: white;
}

form.form-it {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 450px;
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

h1 {
  font-size: 80px;
  margin: 0 0 20px 0;
}

h2 {
  font-size: 32px;
}
</style>
