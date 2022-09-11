<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>
        <table class="table table-bordered" id="datatable" v-else>
            <thead>
            <tr>
                <th>Этапы проекта</th>
                <th>Текущий этап</th>
            </tr>
            </thead>
            <tbody>
            <tr v-if="projectStage.length == 0">
                <td colspan="5">Нет данных</td>
            </tr>
            <tr v-for="(item, index) in projectStage" :key="item.id">
                <td>{{ item.name }}</td>
                <td v-if="item.id == stage_id">&#9989;</td>
                <td v-else> - </td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

<script>

import axios from 'axios';
import inputSelect from "../other/selects/inputSelect";
import {createToaster} from "@meforma/vue-toaster";

export default {
    name: 'projectStage',

    components: {
        inputSelect,
        createToaster
    },
    props: {
        user_id: {
            type: Number
        },
        project_id: {
            type: Number
        },
    },
    computed: {},
    methods: {
        // Получения спика Этап
        loadProjectStage() {
            axios
                .get(`../../project/getStage`)
                .then((response) => {
                    this.projectStage = response.data;
                })
        },

        loadProjectById() {
            this.loading = true
            axios
                .get(`../../project/getProjectById/${this.project_id}`)
                .then((response) => {
                    this.loading = false;
                    this.stage_id = response.data.stage_id;
                })
        }

    },
    mounted() {
        this.loadProjectStage()
        this.loadProjectById()
    },
    data: function () {
        return {
            info: '',
            loading: false,
            projectStage: [],
        }
    },
}
</script>
