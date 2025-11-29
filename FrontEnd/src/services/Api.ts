import axios from 'axios'

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL as string,
    timeout: 32000,
})

export const ApiService = {
    async getStations() {
        return api.get('/stations')
    },

    async getRoute(start: string, end: string) {
        return api.get('/routes', {
            params: { start, end }
        })
    },
}

export default api
