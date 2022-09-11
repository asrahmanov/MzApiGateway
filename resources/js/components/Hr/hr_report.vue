<template>

    <!--    <div class="spinner-border text-primary" role="status" v-if="loading">-->
    <!--        <span class="sr-only">Loading...</span>-->
    <!--    </div>-->
    <!--    <div class="alert alert-fill-success" role="alert" v-if="success">-->
    <!--        Успешно-->
    <!--    </div>-->

    <!--    <div class="alert alert-fill-danger" role="alert" v-if="error!=''">-->
    <!--        {{ error }}!-->
    <!--    </div>-->
    {{ info }}
    <div class="col-4">
        <div class="form-group">
            <label>Выберите ДЗО</label>
            <select class="form-control" v-model="company_id" @change="">
                <option value="0">Выберите ДЗО</option>
                <option v-for="item in filteredCompany" :value="item.id">{{ item.name }}</option>
            </select>
        </div>
    </div>
    <div class="col-4">
        <div class="form-group">
            <label>Выберите дату отчета</label>
            <input type="date" v-model="report_day" class="form-control">
        </div>
    </div>

    <div class="col-4">
        <div class="form-group">
            <label>Выберите тип отчета</label>
            <select v-model="type_id" class="form-control mb-5">
                <option v-for="item in report_type" :value="item.id">{{ item.name }}</option>
            </select>
        </div>
    </div>


    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 1">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Наименование проверяющего органа</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Основание проверки</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>

                <div class="form-group">
                    <label>Дата начала</label>
                    <input type="date" class="form-control" v-model="date_1" placeholder="Дата начала">
                </div>

                <div class="form-group">
                    <label>Результат проверки</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_3"></textarea>
                </div>


            </div>
        </div>
    </div>


    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 2">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Уровень и должность руководителя,</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Информация о наличии тяжелой болезни или причину смерти</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 3">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Должность руководителя</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>дата назначения</label>
                    <input type="date" class="form-control" v-model="date_1" placeholder="Дата назначения">
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 4">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Должность руководителя</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Дата освобождения от должности</label>
                    <input type="date" class="form-control" v-model="date_1"
                           placeholder="Дата освобождения от должности">
                </div>

                <div class="form-group">
                    <label>Причина освобождения от должности</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 5">
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label>Дата травмы</label>
                    <input type="date" class="form-control" v-model="date_1" placeholder="Дата травмы">
                </div>

                <div class="form-group">
                    <label>Предполагаемые причины</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Степень тяжести</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>

                <div class="form-group">
                    <label>Должность сотрудника</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_3"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 6">
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <label>Судебное решение</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Должность сотрудника</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 7">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Краткие результаты</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 8">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Должность руководителя</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Причина привлечения к дисциплинарной ответственности</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 9">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Дата c</label>
                    <input type="date" class="form-control" v-model="date_1" placeholder="Дата c">
                </div>

                <div class="form-group">
                    <label>Дата по</label>
                    <input type="date" class="form-control" v-model="date_2" placeholder="Дата по">
                </div>

                <div class="form-group">
                    <label>Сумма задолженности</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 10">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>График работы</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

                <div class="form-group">
                    <label>Комментарий</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_2"></textarea>
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 11">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Должность</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12 grid-margin stretch-card" v-if="type_id == 12">
        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label>Указать вопросы, которые требуют решения на уровне УК</label>
                    <textarea cols="30" rows="10" class="form-control" v-model="text_1"></textarea>
                </div>

            </div>
        </div>
    </div>

    <button type="button" class="btn btn-primary mr-3 mb-3" @click="Save()" v-if="type_id != 0">Сдать отчет</button>


    <div class="col-12">
        <hr_report_view :user_id="user_id" :report_day="report_day" :company_id="company_id" :load="load"></hr_report_view>
    </div>
</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";
import hr_report_view from "./hr_report_view";
export default {
    components: {
        createToaster,
        hr_report_view
    },
    name: "hr_report",
    props: [
        'user_id'
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            loading: false,
            info: "",
            company_id: 0,

            report_type: [],

            filteredCompany: [],
            company: [],
            report_day_save: "",

            report_day: '',

            type_id: 0,

            // Проверки предприятий по линии персонала
            text_1: '',
            text_2: '',
            text_3: '',
            text_4: '',
            date_1: '',
            date_2: '',
            date_3: '',
            date_4: '',
            load: false,


        }
    },
    mounted() {
        this.loadCompany()
        this.getReportType()
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

        getReportType() {
            this.report_type = [];
            axios
                .get(`../../hr-report-types`)
                .then((response) => {
                    this.report_type = response.data;
                })
        },

        Save() {

            const toaster = createToaster({position: 'top-right'});
            this.loading = true

            if (this.company_id == 0) {
                toaster.error( 'Выберите компанию')
            } else if (this.report_day == '') {
                toaster.error( 'Выберите дату события')
            } else if (this.report_type_id == 0) {
                toaster.error( 'Выберите тип события')
            } else {

                let data = {
                    company_id: this.company_id,
                    report_day: this.report_day,
                    report_type_id: this.type_id,
                    text_1: this.text_1,
                    text_2: this.text_2,
                    text_3: this.text_3,
                    text_4: this.text_4,
                    date_1: this.date_1,
                    date_2: this.date_2,
                    date_3: this.date_3,
                    date_4: this.date_4,
                }
                axios.post(`../../hr-report`, data)
                    .then(response => {
                        if (!response.data.errors) {
                            toaster.success(`Успешно`);
                            this.loading = false
                            this.text_1 = '';
                            this.text_2 = '';
                            this.text_3 = '';
                            this.text_4 = '';
                            this.date_1 = '';
                            this.date_2 = '';
                            this.date_3 = '';
                            this.date_4 = '';
                            this.type_id = 0;
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
    computed: {},



}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
