import { createApp } from 'vue';
import {createRouter, createWebHistory} from 'vue-router'

import routes from './routes'

import App from './views/layouts/App'


const router = createRouter({
    history: createWebHistory(),
    routes,
})

const app = createApp(App)

app.use(router)

app.mount('#app')
