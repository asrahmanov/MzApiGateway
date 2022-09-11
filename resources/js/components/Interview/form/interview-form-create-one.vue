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

    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>


    <div class="col-12" v-show="!loading">
        <div class="mb-5">
            <label>Название формы</label>
            <h3><input type="text" class="form-control" v-model="report_name"></h3>
        </div>

        <div class="form-group mt-5" >
            <button class="btn btn-success" @click="createItem()" >Добавить</button>
        </div>

        <table class="vtl-table table table-hover table-bordered table-responsive-sm" >
            <thead>
            <tr>
                <th class="wordspace">№</th>
                <th class="wordspace">Название</th>
                <th class="wordspace">Тип</th>
                <th class="wordspace">Действие</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, index) in questions">
                <td>{{ index + 1 }}</td>
            <td class="wordspace"> <input type="text" v-model="item.name" class="form-control"></td>
                <td class="wordspace"> <select v-model="item.type" class="form-control">
                    <option value="text">Текст</option>
                    <option value="number">Значение</option>
                </select></td>
                <td class="wordspace"><button class="btn btn-danger" @click="deleteItem(index)" >Удалить</button></td>
            </tr>


            </tbody>
        </table>

        <div class="form-group mt-5" >
            <button class="btn btn-success" @click="save()" >Сохранить</button>
        </div>

    </div>


    <!--        <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table">-->
    <!--            <thead>-->
    <!--            <tr>-->
    <!--                <th class="wordspace">Наименование формы </th>-->
    <!--                <th class="wordspace">Дейстие</th>-->
    <!--            </tr>-->
    <!--            </thead>-->
    <!--            <tbody>-->
    <!--            <tr v-for="item in report">-->
    <!--                <td>{{ item.name }}</td>-->
    <!--                <td>{{ item.status }}</td>-->
    <!--                <td><a class="btn btn-success" :href="'../../interview-worksheets/view/' + item.id ">Открыть</a></td>-->
    <!--            </tr>-->
    <!--            </tbody>-->
    <!--        </table>-->
    <!--    </div>-->
</template>


<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "interview-form-view-one",
    props: [
        'user_id',
        'id',
    ],

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            loading: false,
            report_day: '',
            company_id: 17,
            plan_of_transfer_to_otk: 0,
            report_name: '',
            filteredCompany: [],
            company: [],
            report: [],
            questions: [],
            filteredquestions: [],
            form: '',
            error: '',
            edit: false


        }
    },
    mounted() {
        // this.loadCompany();
        // this.load();


    },
    methods: {

        deleteItem(index) {
            this.questions.splice(index);
        },

        createItem(){
            this.questions.push({name: '', type: 'text', answer:''})
        },

        checkUser(){
          if(this.user_id == this.report.user_id ){
              this.edit = true
          }
        },

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
                .get(`../../interview-form/get-by-id/${this.id}`)
                .then((response) => {
                    this.report_name = response.data[0].name
                    this.report = response.data[0]
                    this.questions = JSON.parse(response.data[0].form);
                    this.checkUser();
                    this.loading = false;
                })
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


        save() {
            this.error = 0
            const toaster = createToaster({position: 'top-right'});

            this.loading = true
            if (this.error == 1) {
                toaster.error(`Заполните все поля`);
            } else {

                let data = {
                    name:this.report_name,
                    form: JSON.stringify(this.questions),
                };

                axios.post(`../../interview-form/edit`, data)
                    .then(response => {
                        if (response.data.id) {
                            toaster.success(`Успешно`);
                            this.loading = false
                            this.editForm = false


                        } else {
                            if (response.data.errors) {
                                toaster.error(response.data.errors);
                            } else {
                                toaster.error('Ошибка сервера');
                            }
                            this.loading = false
                            this.editForm = false
                        }
                    })
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
