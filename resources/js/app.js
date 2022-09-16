import {createApp, defineAsyncComponent} from 'vue'
import mitt from 'mitt';

const emitter = mitt();
window.eventBus = emitter


import VueApexCharts from "vue3-apexcharts"
import auth from "./components/User/auth"

const app = createApp({
    components: {
        auth
    }
},);

app.config.devtools = true;
app.use(VueApexCharts);
app.mount('#app2');

require('./bootstrap');
