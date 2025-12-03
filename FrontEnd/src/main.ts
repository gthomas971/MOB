import { createApp } from 'vue'
import './style.css'
import router from './router'
import App from './App.vue'
import { createPinia } from 'pinia'
import { useStationStore } from './stores/useStation.ts';
import { initAuth } from './services/Api.ts'

const app = createApp(App)
app.use(createPinia())
app.use(router)

await initAuth()

const stationStore = useStationStore();
stationStore.fetchStations().then()

app.mount('#app');