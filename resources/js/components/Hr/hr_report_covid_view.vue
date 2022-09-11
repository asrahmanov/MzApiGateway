<template>
    <div class="col-6">
        <div class="form-group">
            <label>Выберите ДЗО</label>
            <select class="form-control" v-model="company_id">
                <option value="0">Выберите ДЗО</option>
                <option v-for="item in filteredCompany" :value="item.id">{{ item.name }}</option>
            </select>
        </div>
    </div>
    <div class="col-6">
        <div class="form-group">
            <label>Выберите дату отчета</label>
            <input type="date" v-model="report_day" class="form-control">
        </div>
    </div>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="col-12" v-show="!loading">
        <button class="btn btn-behance mb-3" @click="ImportExel()">ImportExel</button>
        <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table">
            <thead>
            <tr>
                <th class="wordspace">Дата</th>
                <th class="wordspace">ДЗО</th>
                <th class="wordspace">Списочная численность сотрудников (без учета внешних и внутренних совместителей)
                </th>
                <th class="wordspace">Всего больничные</th>
                <th class="wordspace">в т.ч. COVID-19 (карантин, самоизоляция)</th>
                <th class="wordspace">Количество летальных случаев от COVID-19</th>
                <th class="wordspace">Количество выздоровевших (накопительным итогом)</th>
                <th class="wordspace">Количество полностью вакцинированных работников (всеми компонентами)</th>
                <th class="wordspace">Количество работников, болеющих COVID-19</th>
                <th class="wordspace">В том числе из фактически вышедших на рабочее место в течение последних 14 дней
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in report">
                <td>{{ item.report_day }}</td>
                <td>{{ getCompanyName(item.company_id).name }}</td>
                <td>{{ item.n_1 }}</td>
                <td>{{ item.n_2 }}</td>
                <td>{{ item.n_3 }}</td>
                <td>{{ item.n_4 }}</td>
                <td>{{ item.n_5 }}</td>
                <td>{{ item.n_6 }}</td>
                <td>{{ item.n_7 }}</td>
                <td>{{ item.n_8 }}</td>
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
    name: "hr_report_covid_view",
    props: [
        'user_id'
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,

            report_day: '',
            n_1: 0,
            n_2: 0,
            n_3: 0,
            n_4: 0,
            n_5: 0,
            n_6: 0,
            n_7: 0,
            n_8: 0,

            company_id: 0,

            filteredCompany: [],
            company: [],
            report: [],


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
            if(this.report_day == '') {
                toaster.error('Заполните дату отчета');
            } else if (this.company_id == 0) {
                toaster.error('Выберите ДЗО');
            } else {
                location.href = `../../hr-report-covid/ImportExel/${this.report_day}/${this.company_id}`
            }
        },


        loadReport() {
            this.loading = true
            this.report = [];
            axios
                .get(`../../hr-report-covid/get-by-report-day/${this.report_day}/${this.company_id}`)
                .then((response) => {
                    this.report = response.data;
                    this.loading = false;
                })
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
