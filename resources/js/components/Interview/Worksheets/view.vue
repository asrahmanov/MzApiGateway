<template>
    <div class="col-6" style="display: none">
        <div class="form-group">
            <label>Выберите ДЗО</label>
            <select class="form-fact_of_transfer_to_warehouse" v-model="company_id">
                <option value="0">Выберите ДЗО</option>
                <option v-for="item in filteredCompany" :value="item.id">{{ item.name }}</option>
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
                <th class="wordspace">№ </th>
                <th class="wordspace">ДЗО </th>
<!--                <th class="wordspace">ФИО </th>-->
                <th class="wordspace">Наименование формы </th>
                <th class="wordspace">Статус</th>
                <th class="wordspace">Действие</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index ) in filteredreport">
                <td class="wordspace">{{ index + 1 }}</td>
                <td class="wordspace">{{ getUserName(item.user_id).company[0].name }}</td>
<!--                <td class="wordspace">{{ // getUserName(item.user_id).last_name }} {{ getUserName(item.user_id).first_name }}</td>-->
                <td class="wordspace">{{ item.name }}</td>
                <td class="wordspace"><span class="badge badge-primary" style="font-size: 14px;" v-show="item.status == 'new'">Не заполнено</span>
               <span class="badge badge-success" style="font-size: 14px;" v-show="item.status == 'close'">Заполнено</span></td>
                <td class="wordspace">
                    <a v-show="item.status == 'new'" class="btn btn-success" target="_blank" :href="'../../interview-worksheets/view/' + item.id ">Открыть</a>
                    <a v-show="item.status == 'close'" class="btn btn-success" target="_blank" :href="'../../interview-worksheets/view/' + item.id ">Редактировать</a>
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
    name: "interview-view",
    props: [
        'user_id',
        'status'
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,

            report_day: '',
            company_id: 17,
            plan_of_transfer_to_otk: 0,

            filteredCompany: [],
            company: [],
            report: [],
            filteredreport: [],
            filteredUsers: [],
            users: [],



        }
    },
    mounted() {
        this.loadUsers()
        this.loadCompany();
        this.load();

    },
    methods: {

        loadCompany() {
            this.filteredCompany = [];
            this.company = [];
            let data = {
                company_id: 1
            }
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.company = response.data;
                    this.filteredCompany = response.data;
                })
        },


        load() {
            this.loading = true
            this.report = [];

            axios
                .get(`../../interview-worksheets/get-by-userId/${this.user_id}`)
                .then((response) => {
                    this.report = response.data;
                    this.filteredreport = this.report.filter((el) => {
                        if(el.status == this.status){
                            return true
                        }
                    })
                    this.loading = false;
                })
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


        getCompanyName(company_id) {
            if (company_id == 0) {
                return 'ДЗО не выбран'
            }
            return this.company.find(el => {
                if (el.id == company_id) {
                    return true
                }
            })
        },


    },
    computed: {},

    watch: {

    }

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
