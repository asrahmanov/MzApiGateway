<template xmlns="http://www.w3.org/1999/html">

    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>


    <div class="col-12" v-show="!loading">

        <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table">
            <thead>
            <tr>
                <th class="wordspace">№</th>
                <th class="wordspace">Название</th>
                <th class="wordspace">Действие</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index ) in filteredreport">
                <td class="wordspace">{{ index + 1}}</td>
                <td class="wordspace">{{ item.name }}</td>
                <td class="wordspace"><a class="btn btn-success" target="_blank"
                                         :href="'../../interview-form/view/' + item.id ">Открыть</a>

                    <button class="btn btn-danger ml-5" @click="deleteItem(item.id)"
                       >Удалить</button>
                </td>
            </tr>
            </tbody>
        </table>


    </div>
</template>


<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "interview-form-all",
    props: [
        'user_id',
        'status',
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            loading: false,

            report_day: '',
            company_id: 17,
            plan_of_transfer_to_otk: 0,

            filteredCompany: [],
            company: [],
            report: [],
            filteredreport: [],
            typeReport: [],
            report_name: 'Все формы',
            filteredUsers: [],
            users: [],

        }
    },
    mounted() {
        this.load();
    },
    methods: {
        deleteItem(id){
            this.loading = true
            axios
                .delete(`../../interview-form/${id}`)
                .then((response) => {
                   this.load();
                })
        },
        filerDate(date){
            return `${(new Date(date)).toLocaleDateString()}   ${new Date(date).getHours()}: ${new Date(date).getMinutes()}`
        },
        jsonParse(item){
            return JSON.parse(item)
        },
        filterReport(){
            if(this.report_name == "Все отчеты") {
                this.filteredreport = this.report
            } else {
                this.filteredreport = this.report.filter(el => {
                    if(el.name == this.report_name){
                        return true
                    }
                })
            }
        },




        load() {
            this.loading = true
            this.report = [];
            axios
                .get(`../../interview-form`)
                .then((response) => {
                    this.report = response.data;
                    this.filteredreport = this.report;
                    this.loading = false;
                })
        },

    },
    computed: {},

    watch: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
