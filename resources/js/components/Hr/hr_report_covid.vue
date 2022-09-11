<template>

        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

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




    <table class="table table-bordered" id="datatable" v-if="!loading">
        <thead>
        <tr>
            <th>Списочная численность сотрудников (без учета внешних и внутренних совместителей)</th>
            <th>Всего больничные</th>
            <th>в т.ч. COVID-19 (карантин, самоизоляция)</th>
            <th>Количество летальных случаев от COVID-19</th>
            <th>Количество выздоровевших (накопительным итогом)</th>
            <th>Количество полностью вакцинированных работников (всеми компонентами)</th>
            <th>Количество работников, болеющих COVID-19</th>
            <th>В том числе из фактически вышедших на рабочее место в течение последних 14 дней</th>
            <th>Действие</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><input type="number" v-model="n_1" class="form-control"></td>
            <td><input type="number" v-model="n_2" class="form-control"></td>
            <td><input type="number" v-model="n_3" class="form-control"></td>
            <td><input type="number" v-model="n_4" class="form-control"></td>
            <td><input type="number" v-model="n_5" class="form-control"></td>
            <td><input type="number" v-model="n_6" class="form-control"></td>
            <td><input type="number" v-model="n_7" class="form-control"></td>
            <td><input type="number" v-model="n_8" class="form-control"></td>
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

            let data = {
                report_day: this.report_day,
                company_id: this.company_id,
                n_1: this.n_1,
                n_2: this.n_2,
                n_3: this.n_3,
                n_4: this.n_4,
                n_5: this.n_5,
                n_6: this.n_6,
                n_7: this.n_7,
                n_8: this.n_8
            }

            axios.post(`../../hr-report-covid`, data)
                .then(response => {
                    if (!response.data.errors) {
                        toaster.success(`Успешно`);
                        this.loading = false
                        this.n_1 = 0;
                        this.n_2 = 0;
                        this.n_3 = 0;
                        this.n_4 = 0;
                        this.n_5 = 0;
                        this.n_6 = 0;
                        this.n_7 = 0;
                        this.n_8 = 0;
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
