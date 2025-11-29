<template>
  <div class="block-form" ref="container">
    <label :for="id">{{ label }}</label>
    <input
        :id="id"
        type="text"
        v-model="inputValue"
        @input="onInput"
        autocomplete="off"
    />

    <ul v-if="suggestions.length" class="suggestions">
      <li
          v-for="item in suggestions"
          :key="item.id"
          @click="selectItem(item)"
      >
        {{ item.long_name }} ({{ item.short_name }})
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted, onBeforeUnmount } from "vue";
import type { Station } from '@/stores/useStation.ts';


const props = defineProps<{
  label: string;
  id: string;
  modelValue: string;
  stations: Station[];
}>();

const emits = defineEmits<{
  (e: "update:modelValue", value: string): void;
}>();

const suggestions = ref<Station[]>([]);
const inputValue = ref(props.modelValue);
const container = ref<HTMLElement | null>(null);

watch(() => props.modelValue, (val) => {
  inputValue.value = val;
});

function onInput() {
  emits("update:modelValue", inputValue.value);
  if (inputValue.value?.length < 2) {
    suggestions.value = [];
    return;
  }
  suggestions.value = props.stations.filter((station) =>
      station.long_name.toLowerCase().includes(inputValue.value?.toLowerCase())
  );
}

function selectItem(station: Station) {
  emits("update:modelValue", station.long_name);
  suggestions.value = [];
}

function handleClickOutside(event: MouseEvent) {
  if (container.value && !container.value.contains(event.target as Node)) {
    suggestions.value = [];
  }
}

onMounted(() => {
  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
.block-form {
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 5px;
  font-size: 18px;
}

.block-form label {
  position: absolute;
  top: -11px;
  left: 15px;
  background-color: #2A4463;
  padding: 0 8px;
  height: 22px;
  color: white;
}

input {
  height: 50px;
  background: transparent;
  color: white;
  border: 2px solid white;
  border-radius: 8px;
  font-size: 18px;
  padding: 0 15px;
}

.suggestions {
  position: absolute;
  top: 35px;
  padding: 0;
  width: 100%;
  background: #2A4463;
  color: white;
  list-style: none;
  border-radius: 5px;
  max-height: 250px;
  overflow-y: auto;
  border: 1px solid #ccc;
  z-index: 999;
}
.suggestions li {
  padding: 8px;
  cursor: pointer;
}
.suggestions li:hover {
  background: #364f75;
}
</style>
