import { createRouter, createWebHistory } from 'vue-router';
import HomeView from './../views/Home.vue';
import StatsView from './../views/Stats.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: HomeView
    },
    {
        path: '/stats',
        name: 'Stats',
        component: StatsView
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
