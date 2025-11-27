import axios from 'axios'

const api = axios.create({
    baseURL: 'http://localhost:8080',
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

    async getStation(id: number) {
        return api.get(`/stations/${id}`)
    },

    async createStation(payload: any) {
        return api.post('/stations', payload)
    },

    async updateStation(id: number, payload: any) {
        return api.put(`/stations/${id}`, payload)
    },

    async deleteStation(id: number) {
        return api.delete(`/stations/${id}`)
    },
}

export default api
