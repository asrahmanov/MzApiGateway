<template>
    <div class="col-6">
        <div class="form-group">
            <label>Выберите отчет</label>
            <select class="form-fact_of_transfer_to_warehouse" v-model="report_name" @change="filterReport()">
                <option value="Все отчеты">Все отчеты</option>
                <option v-for="item in typeReport" :value="item">{{ item }}</option>
            </select>
        </div>
    </div>

    <div class="col-6">

    </div>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>


    <div class="col-12" v-show="!loading">

        <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table">
            <thead>
            <tr>
                <th class="wordspace">№</th>
                <th class="wordspace">ДЗО</th>
<!--                <th class="wordspace">ФИО</th>-->
<!--                <th class="wordspace">Должность</th>-->
<!--                <th class="wordspace">Наименование отчета</th>-->
<!--                <th class="wordspace">Дата создания</th>-->
<!--                <th class="wordspace">Дата исполнения</th>-->
                <th class="wordspace">Результат</th>
<!--                <th class="wordspace">Статус</th>-->
                <th class="wordspace">Действие</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index ) in filteredreport">
                <td class="wordspace">{{ index + 1}}</td>

                <td class="wordspace"><span v-show="item.company">{{ item.company }}</span></td>
<!--                <td class="wordspace"><span v-show="item.last_name">{{ item.last_name }} {{ item.first_name }}</span>   </td>-->
<!--                <td class="wordspace"><span v-show="item.job_title">{{ item.job_title }}</span></td>-->
<!--                <td class="wordspace">{{ item.name }}</td>-->
<!--                <td class="wordspace">-->
<!--                    <span class="badge badge-primary" style="font-size: 14px;"-->
<!--                          v-show="item.status == 'new'">{{ item.status }}</span>-->
<!--                    <span class="badge badge-success" style="font-size: 14px;"-->
<!--                          v-show="item.status == 'close'">{{ item.status }}</span>-->
<!--                </td>-->
<!--                <td class="wordspace">{{ filerDate(item.created_at) }}</td>-->
<!--                <td class="wordspace">{{ filerDate(item.updated_at) }}</td>-->
                <td>
                    <p v-for="em in jsonParse(item.form_questions)">{{ em.name }}: {{ em.answer }}</p>
                </td>
                <td class="wordspace"><a class="btn btn-success" target="_blank"
                                         :href="'../../interview-worksheets/view/' + item.id ">Открыть</a></td>
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
    name: "interview-view-all",
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
            report_name: 'Все отчеты',
            filteredUsers: [],
            users: [],

        }
    },
    mounted() {
        this.loadUsers();
    },
    methods: {

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
                .get(`../../interview-worksheets`)
                .then((response) => {
                    this.report = response.data;
                    for(let i =0; i < this.report.length; i++) {
                        this.report[i].company = this.getUserName(this.report[i].user_id).company[0].name
                        this.report[i].last_name = this.getUserName(this.report[i].user_id).last_name
                        this.report[i].first_name = this.getUserName(this.report[i].user_id).first_name
                        this.report[i].job_title = this.getUserName(this.report[i].user_id).job_title
                    }

                    this.filteredreport = this.report;
                    this.loading = false;
                    this.renderTypreReport()
                })
        },
        renderTypreReport() {
            for (let i = 0; i < this.report.length; i++) {
                if(!this.typeReport.includes(this.report[i].name)){
                    this.typeReport.push(this.report[i].name)
                }

            }
        },

      async loadUsers() {

            this.filteredUsers = [];
            this.users = [];
            this.loading = true
            let data = {
                company_id: this.company_id
            }
            axios.post(`../../users/dzo`, data)
                .then(response => {
                    this.info = response;
                    for (let key in response.data) {
                        this.users.push(response.data[key]);
                        this.filteredUsers.push(response.data[key]);
                    }
                    this.loading = false
                }).then(() => {
                    this.load()
            })
        },

        getUserName(user_id){
            let obj = this.users.find(el => {
                if (el.id == user_id) {
                    return el
                }
            });

            if (typeof obj == "undefined") {
                return {
                    company: [{name: ''}],
                    last_name: '',
                    first_name: '',
                    job_title: '',
                }

            } else {
                return obj
            }

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
