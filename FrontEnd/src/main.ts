import { createApp } from 'vue'
import './style.css'
import router from './router'
import App from './App.vue'
import { createPinia } from 'pinia'
import { useStationStore } from './stores/useStation.ts';

const app = createApp(App)
app.use(createPinia())
app.use(router)

const stationStore = useStationStore();

stationStore.fetchStations().then()

app.mount('#app');