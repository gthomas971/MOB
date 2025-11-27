import { defineStore } from 'pinia';
import { ApiService } from '@/services/Api.ts';
import { ref } from 'vue';

export interface Station {
    id: number;
    short_name: string;
    long_name: string;
}

export const useStationStore = defineStore('station', () => {
    const stations = ref<Station[]>([]);
    const loading = ref(false);
    const error = ref<string | null>(null);

    async function fetchStations() {
        loading.value = true;
        error.value = null;
        try {
            const { data } = await ApiService.getStations();
            stations.value = data.data;
        } catch (err: any) {
            error.value = err.message || 'Erreur lors du chargement';
        } finally {
            loading.value = false;
        }
    }

    return { stations, loading, error, fetchStations };
});
