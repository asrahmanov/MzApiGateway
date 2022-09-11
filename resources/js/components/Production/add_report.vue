<template>

    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>

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
        <div class="form-group">
            <label>Выберите дату отчета</label>
            <input type="date" v-model="report_day" @change="loadOneReport()" class="form-control">
        </div>
    </div>

    <div class="col-6">
        <div class="form-group">
            <label>ПЛАН ПЕРЕДАЧИ В ОТК, В ШТ. (на месяц)</label>
            <input type="number" v-model="plan_of_transfer_to_otk" class="form-control">
        </div>
    </div>


    <table class="table table-bordered" id="datatable" v-if="!loading">
        <thead>
        <tr>
            <th class="wordspace">ПЛАН ЗАПУСКА В ШТ.</th>
            <th class="wordspace">ПЛАН ЗАПУСКА ПО ССЗ</th>
            <th class="wordspace">ФАКТ ЗАПУСКА В ШТ.</th>
            <th class="wordspace">ОПРОБОВАНИЯ</th>
            <th class="wordspace">ФАКТ ПЕРЕДАЧИ В ОТК, В ШТ. </th>
            <th class="wordspace">ФАКТ ПЕРЕДАЧИ НА СКЛАД В ШТ.</th>
            <th class="wordspace">РАНЕЕ ЗАПУЩЕННЫЕ - ПО СОПРОВОДИТЕЛЬНЫМ ЛИСТАМ (ДЛЯ ПОДСЧЁТА ВЫХОДА ГОДНЫХ)</th>


        </tr>
        </thead>
        <tbody>
        <tr>
            <td class="wordspace"><input type="number" v-model="launch_plan" class="form-control"></td>
            <td><input type="number" v-model="launch_plan_ssz" class="form-control"></td>
            <td><input type="number" v-model="launch_fact" class="form-control"></td>
            <td><input type="number" v-model="sampling" class="form-control"></td>
            <td><input type="number" v-model="fact_of_transfer_to_otk" class="form-control"></td>
            <td><input type="number" v-model="fact_of_transfer_to_warehouse" class="form-control"></td>
            <td><input type="number" v-model="launch_previously" class="form-control"></td>
            <td>
                <button type="button" class="btn btn-primary" @click="Save()">Сдать отчет</button>
            </td>
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

    name: "add_report",
    props: [
        'user_id'
    ],

    // "user_id",
    // "company_id",
    // "report_day",
    // "launch_plan", // ПЛАН ЗАПУСКА В ШТ. (на сегодняшний день)  -  launch_plan
    // "launch_fact", // ФАКТ ЗАПУСКА В ШТ. (на сегодняшний день) - launch_fact
    // "fact_of_transfer_to_otk", // ФАКТ ПЕРЕДАЧИ В ОТК, В ШТ. (на предыдущий день) - fact_of_transfer_to_otk
    // "fact_of_transfer_to_warehouse", // ФАКТ ПЕРЕДАЧИ НА СКЛАД В ШТ. (на предыдущий день) - fact_of_transfer_to_warehouse
    // "launch_previously", // РАНЕЕ ЗАПУЩЕННЫЕ - ПО СОПРОВОДИТЕЛЬНЫМ ЛИСТАМ (ДЛЯ ПОДСЧЁТА ВЫХОДА ГОДНЫХ) - launch_previously
    // "plan_of_transfer_to_otk", // ПЛАН ПЕРЕДАЧИ В ОТК, В ШТ. (на месяц) // plan_of_transfer_to_otk


    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            loading: false,
            company_id: 17,
            launch_plan: 0,
            launch_fact: 0,
            fact_of_transfer_to_otk: 0,
            fact_of_transfer_to_warehouse: 0,
            launch_previously: 0,
            plan_day: 0,
            plan_of_transfer_to_otk: 0,
            launch_plan_ssz: 0,
            sampling: 0,
            report_day: '',
            filteredCompany: [],
            company: [],

            report: [],

        }
    },
    mounted() {
        this.loadCompany()
    },
    methods: {
        loadOneReport() {

            this.launch_plan = 0
            this.launch_fact = 0
            this.fact_of_transfer_to_otk = 0
            this.fact_of_transfer_to_warehouse = 0
            this.launch_previously = 0
            this.plan_of_transfer_to_otk = 0
            this.launch_plan_ssz = 0
            this.sampling = 0

            axios
                .get(`../../production-report/get-by-report-day/${this.report_day}/${this.company_id}`)
                .then((response) => {
                    if (response.data[0]) {
                        this.launch_plan = response.data[0].launch_plan
                        this.launch_fact = response.data[0].launch_fact
                        this.fact_of_transfer_to_otk = response.data[0].fact_of_transfer_to_otk
                        this.fact_of_transfer_to_warehouse = response.data[0].fact_of_transfer_to_warehouse
                        this.launch_previously = response.data[0].launch_previously
                        this.launch_plan_ssz = response.data[0].launch_plan_ssz
                        this.sampling = response.data[0].sampling
                        // this.plan_of_transfer_to_otk = response.data[0].plan_of_transfer_to_otk
                    } else {
                        this.launch_plan = 0
                        this.launch_fact = 0
                        this.fact_of_transfer_to_otk = 0
                        this.fact_of_transfer_to_warehouse = 0
                        this.launch_previously = 0
                        this.plan_of_transfer_to_otk = 0
                        this.launch_plan_ssz = 0
                        this.sampling = 0
                    }

                    let dasArray = this.report_day.split('-')
                    let firstDay = `${dasArray[0]}-${dasArray[1]}-01`

                    axios
                        .get(`../../production-report/get-by-report-between/${firstDay}/${this.report_day}/${this.company_id}`)
                        .then((response) => {
                            if (response.data[0]) {
                                this.plan_of_transfer_to_otk = response.data[0].plan_of_transfer_to_otk
                            }
                        })
                })
        },

        loadCompany() {
            this.filteredCompany = [];
            this.company = [];
            let data = {
                company_id: 0
            }

            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.company = response.data;
                    this.filteredCompany = response.data;
                })

        },


        Save() {
            const toaster = createToaster({position: 'top-right'});
            if (this.report_day == '') {
                toaster.error('Заполните дату отчета');
            } else if (this.company_id == 0) {
                toaster.error('Выберите ДЗО');
            } else {

                let data = {
                    report_day: this.report_day,
                    company_id: this.company_id,
                    user_id: this.user_id,
                    launch_plan: this.launch_plan,
                    launch_fact: this.launch_fact,
                    fact_of_transfer_to_otk: this.fact_of_transfer_to_otk,
                    fact_of_transfer_to_warehouse: this.fact_of_transfer_to_warehouse,
                    launch_previously: this.launch_previously,
                    plan_of_transfer_to_otk: this.plan_of_transfer_to_otk,
                    launch_plan_ssz: this.launch_plan_ssz,
                    sampling: this.sampling,
                }

                axios.post(`../../production-report`, data)
                    .then(response => {
                        if (!response.data.errors) {
                            toaster.success(`Успешно`);
                            this.loading = false
                            this.launch_plan = 0;
                            this.launch_fact = 0;
                            this.fact_of_transfer_to_otk = 0;
                            this.fact_of_transfer_to_warehouse = 0;
                            this.launch_previously = 0;
                            this.launch_plan_ssz = 0;
                            this.sampling = 0;
                            this.load = true;
                        } else {
                            if (response.data.errors) {
                                toaster.error(response.data.errors);
                            } else {
                                toaster.error('Ошибка сервера');
                            }
                            this.loading = false
                        }
                    })

            }


        },


    },
    computed: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
