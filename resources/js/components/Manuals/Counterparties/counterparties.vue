<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <div v-else>

        <table class="table table-bordered" style="width: 100%;">
            <thead>
            <tr>
                <th>
                    <input type="text" class="form-control" v-model="searchText"
                           placeholder="Наимановние" @input="filter()">
                </th>
                <th>ИНН</th>
                <th>Телефон</th>
                <th>Описание</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="counterparties.length == 0">
                <td colspan="5">Нет данных</td>
            </tr>
            <tr v-for="item in counterpartiesFilter" :key="item.id">
                <td style="max-width: 200px;" class="wordspace">{{ item.name }}</td>
                <td class="wordspace">{{ item.inn }}</td>
                <td class="wordspace">{{ item.phone }}</td>
                <td style="max-width: 200px;" class="wordspace">{{ item.description }}</td>
            </tr>

            </tbody>
        </table>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import inputSelect from "../../other/selects/inputSelect";
import {createToaster} from "@meforma/vue-toaster";

export default {
    name: 'counterparties',

    components: {
        inputSelect,
        createToaster
    },
    methods: {
        filter() {
            let regexp = new RegExp(this.searchText, 'i');
            this.counterpartiesFilter = this.counterparties.filter(el => regexp.test(el.name));
        },

        load() {
            this.loading = true;
            axios
                .get("../counterparties/list")
                .then((response) => {
                    this.counterparties = response.data;
                    this.counterpartiesFilter = response.data;
                    this.loading = false
                })
        },
    },
    mounted() {
        this.load();

    },
    data: function () {
        return {
            counterparties: [],
            counterpartiesFilter: [],
            info: '',
            loading: false,
            searchText: '',
        }
    },
}
</script>
