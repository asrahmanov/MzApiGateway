<template>

    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <!--    <div class="alert alert-fill-success" role="alert" v-if="success">-->
    <!--        Успешно-->
    <!--    </div>-->

    <!--    <div class="alert alert-fill-danger" role="alert" v-if="error!=''">-->
    <!--        {{ error }}!-->
    <!--    </div>-->
    {{ info }}
    <div class="row" v-show="!loading">
        <button class="btn btn-behance mb-3" @click="ImportExel()">ImportExel</button>
        <table class="vtl-table table table-hover table-bordered table-responsive-sm ">
            <tr>
                <th>№</th>
                <th style="max-width: 300px !important;">Наименование вопроса</th>
                <th class="wordspace">Комментарии</th>
                <th>Примечание</th>
            </tr>
            <tbody>
            <tr v-for="(item, index) in report_type">
                <td> {{ index + 1 }}</td>
                <td class="wordspace"> {{ item.name }}</td>
                <td class="wordspace"><span v-for="el in getReportInfo(item.id)">{{ getInput(el) }}</span><br></td>
                <td class="wordspace">{{ item.info }}</td>
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
    name: "hr_report_view",
    props: [
        'company_id',
        'user_id',
        'report_day',
        'load',
    ],

    data() {
        return {
            info: "",
            loading: false,
            report_type: [],
            report: [],
            company: [],
        }
    },
    mounted() {
        this.getReportType();
        this.loadCompany();
    },
    methods: {
        ImportExel() {
            const toaster = createToaster({position: 'top-right'});
            if (this.report_day == '') {
                toaster.error('Заполните дату отчета');
            } else if (this.company_id == 0) {
                toaster.error('Выберите ДЗО');
            } else {
                location.href = `../../hr-report/ImportExel/${this.report_day}/${this.company_id}`
            }
        },
        loadCompany() {
            this.company = [];
            let data = {
                company_id: 1
            }
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    this.company = response.data;
                })
        },

        loadReport() {
            this.loading = true
            this.report = [];
            axios
                .get(`../../hr-report/get-by-report-day/${this.report_day}/${this.company_id}`)
                .then((response) => {
                    this.report = response.data;
                    this.loading = false;
                })
        },
        getReportType() {
            this.report_type = [];
            axios
                .get(`../../hr-report-types`)
                .then((response) => {
                    this.report_type = response.data;
                })
        },

        getReportInfo(id) {
            return this.report.filter(el => {
                if (el.report_type_id == id) {
                    return true
                }
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

        getInput(el) {

            if (el.report_type_id == 1) {
                return `${this.getCompanyName(el.company_id).name}
Наименование проверяющего органа: ${el.text_1}
Основание проверки: ${el.text_2}
Дата начала: ${el.date_1}
Результат проверки: ${el.text_3}`
            }

            if (el.report_type_id == 2) {
                return `${this.getCompanyName(el.company_id).name}
Уровень и должность руководителя: ${el.text_1}
Информация о наличии тяжелой болезни или причину смерти: ${el.text_2}`
            }

            if (el.report_type_id == 3) {
                return `${this.getCompanyName(el.company_id).name}
Должность руководителя: ${el.text_1}
дата назначения: ${el.date_1}`
            }

            if (el.report_type_id == 4) {
                return `${this.getCompanyName(el.company_id).name}
Должность руководителя: ${el.text_1}
Дата освобождения от должности: ${el.date_1}
Причина освобождения от должности: ${el.text_2}`
            }

            if (el.report_type_id == 5) {
                return `${this.getCompanyName(el.company_id).name}
Дата травмы: ${el.date_1}
Предполагаемые причины: ${el.text_1}
Степень тяжести: ${el.text_2}
Должность сотрудника: ${el.text_3}`
            }

            if (el.report_type_id == 6) {
                return `${this.getCompanyName(el.company_id).name}
Судебное решение: ${el.text_1}
Должность сотрудника: ${el.text_2}`
            }

            if (el.report_type_id == 7) {
                return `${this.getCompanyName(el.company_id).name}
Краткие результаты: ${el.text_1}`
            }

            if (el.report_type_id == 8) {
                return `${this.getCompanyName(el.company_id).name}
Должность руководителя: ${el.text_1}
Причина привлечения к дисциплинарной ответственности: ${el.text_2}`
            }

            if (el.report_type_id == 9) {
                return `${this.getCompanyName(el.company_id).name}
Дата: ${el.date_1} -  ${el.date_2}
Сумма задолженности: ${el.text_1}`
            }

            if (el.report_type_id == 10) {
                return `${this.getCompanyName(el.company_id).name}
График работы: ${el.text_1}
Комментарий: ${el.text_2}`
            }

            if (el.report_type_id == 11) {
                return `${this.getCompanyName(el.company_id).name}
Должность: ${el.text_1}`
            }

            if (el.report_type_id == 12) {
                return `${this.getCompanyName(el.company_id).name}
Вопросы, которые требуют решения на уровне УК: ${el.text_1}`
            }

            return el


        },


    },
    computed: {},

    watch: {
        report_day(newValue) {
            if (this.company_id != 0 && this.report_day != '') {
                this.loadReport()
            }
        },

        load(newValue) {
            this.loadReport()
        },

    }

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
