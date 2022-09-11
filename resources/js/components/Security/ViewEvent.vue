<template>
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label>Дата</label>
                <input type="date" class="form-control" v-model="event_date" autocomplete="off"
                       placeholder="Дата" @change="loadEvent()"/>
            </div>
        </div>
    </div>


    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>

    <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table" v-if="!loading">
        <thead>
        <tr>
            <th class="wordspace">Дата события</th>
            <th class="wordspace">Время события</th>
            <th class="wordspace">Тип события</th>
            <th class="wordspace">Описание</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in events">
            <td>{{ item.event_date }}</td>
            <td>{{ item.event_time }}</td>
            <td>{{ getNameEvent(item.event_id)[0].name }}</td>
            <td>{{ item.description }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "viewEvent",
    props: [
        'user_id',
    ],


    data() {
        return {
            event_date: '',
            events: [],
            TypeEvents: [],
            loading: false,
            company_id: 1,
        }
    },
    mounted() {
        this.loadTypeEvent();
    },
    methods: {
        loadEvent() {
            this.events = [];
            this.loading = true

            axios
                .get(`../../security-report/get-by-report-day/${this.event_date}/${this.company_id}`)
                .then((response) => {
                    this.events = response.data;
                    this.loading = false

                })
        },
        loadTypeEvent() {
            this.TypeEvents = [];

            axios
                .get(`../../security-event`)
                .then((response) => {
                    this.TypeEvents = response.data;
                })
        },

        getNameEvent(event_id) {
                return this.TypeEvents.filter(el => {
                    if(el.event_id = event_id) {
                        return el
                    }
                })
        }
    },
    computed: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
