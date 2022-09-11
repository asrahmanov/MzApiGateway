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
        <button class="btn btn-behance mb-3" @click="ImportExel()">ImportExel</button>
        <table class="vtl-table table table-hover table-bordered table-responsive-sm">
            <thead>
            <th>Выберите дату отчета
            </th>
            <th> {{ mount }}</th>
            </thead>
            <tbody>
             <td><input type="date" v-model="report_day" class="form-control" @change="getMount()"></td>
             <td>{{ plan_of_transfer_to_otk }}</td>
            </tbody>
        </table>
        <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table">
            <thead>
            <tr>
                <th class="wordspace">Дата</th>
                <th class="wordspace">ПЛАН ЗАПУСКА В ШТ.</th>
                <th class="wordspace">ПЛАН ЗАПУСКА ПО ССЗ</th>
                <th class="wordspace">ФАКТ ЗАПУСКА В ШТ.</th>
                <th class="wordspace">ОПРОБОВАНИЯ</th>
                <th class="wordspace">ФАКТ ПЕРЕДАЧИ В ОТК, В ШТ.</th>
                <th class="wordspace">ФАКТ ПЕРЕДАЧИ НА СКЛАД В ШТ.</th>
                <th class="wordspace">ПРОЦЕНТ ВЫХОДА ГОДНЫХ</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in report">
                <td>{{ item.report_day }}</td>
                <td>{{ item.launch_plan }}</td>
                <td>{{ item.launch_plan_ssz }}</td>
                <td>{{ item.launch_fact }}</td>
                <td>{{ item.sampling }}</td>
                <td>{{ item.fact_of_transfer_to_otk }}</td>
                <td>{{ item.fact_of_transfer_to_warehouse }}</td>
                <td>{{ (item.fact_of_transfer_to_otk / item.launch_previously * 100).toFixed(2) }}</td>
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
    name: "report_view",
    props: [
        'user_id'
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
            mount: '',
            mounts: [
                'Январь',
                'Февраль',
                'Март',
                'Апрель',
                'Май',
                'Июнь',
                'Июль',
                'Август',
                'Сентябрь',
                'Октябрь',
                'Ноябрь',
                'Декабрь',
            ],



        }
    },
    mounted() {
        this.loadCompany()
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
        ImportExel() {
            const toaster = createToaster({position: 'top-right'});
            if (this.report_day == '') {
                toaster.error('Заполните дату отчета');
            } else if (this.company_id == 0) {
                toaster.error('Выберите ДЗО');
            } else {
                location.href = `../../production-report/ImportExel/${this.report_day}/${this.company_id}`
            }
        },

        getMount() {
          this.mount =  this.mounts[new Date(Date.parse(this.report_day)).getMonth()];
        },


        loadReport() {
            this.loading = true
            this.report = [];
            let dasArray = this.report_day.split('-')
            let firstDay = `${dasArray[0]}-${dasArray[1]}-01`

            axios
                .get(`../../production-report/get-by-report-between/${firstDay}/${this.report_day}/${this.company_id}`)
                .then((response) => {
                    this.report = response.data;
                    this.loading = false;
                    this.getPlan_of_transfer_to_otk();
                })
        },

        getPlan_of_transfer_to_otk() {
              this.plan_of_transfer_to_otk = this.report[0].plan_of_transfer_to_otk;
        },

        getCompanyName(company_id) {
            console.log('company_id', company_id)
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
        report_day(newValue) {
            if (this.company_id != 0 && this.report_day != '') {
                this.loadReport()
            }
        },

        company_id(newValue) {
            if (this.company_id != 0 && this.report_day != '') {
                this.loadReport()
            }
        },


    }

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
