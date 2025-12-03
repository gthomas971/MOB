import axios from 'axios'

let token: string | null = null

const api = axios.create({
    baseURL: `${import.meta.env.VITE_API_URL}/api/v1`as string,
    timeout: 32000,
})

export async function initAuth() {
    const response = await api.post('/login', {
        email: import.meta.env.VITE_API_EMAIL,
        password: import.meta.env.VITE_API_PASSWORD,
    })

    token = response.data.token

    api.defaults.headers.common['Authorization'] = `Bearer ${token}`
    console.log("JWT Token chargé")
}

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
