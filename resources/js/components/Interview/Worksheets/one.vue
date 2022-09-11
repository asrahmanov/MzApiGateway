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
    <div class="col-12">
        <h3>{{ report_name }}</h3>
    </div>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>

    <div class="col-6" v-show="!loading">
        <div class="form-group" v-for="(item, index) in questions">
            <label>{{ item.name }}</label>
            <input :type="item.type" class="form-control" :id="'answer_' + item.id" :value="item.answer" v-if="edit == true">
            <input :type="item.type" class="form-control" :id="'answer_' + item.id" :value="item.answer" v-else
                   disabled>
        </div>
        <br>
        <button class="btn btn-success" @click="save()" v-if="edit == true">Сохранить</button>
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
    name: "interview-view-one",
    props: [
        'user_id',
        'id',
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
            questions: [],
            filteredquestions: [],
            form_questions: '',
            error: '',
            edit: false


        }
    },
    mounted() {
        this.loadCompany();
        this.load();


    },
    methods: {
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
                .get(`../../interview-worksheets/get-by-id/${this.id}`)
                .then((response) => {
                    this.report_name = response.data[0].name
                    this.report = response.data[0]
                    this.questions = JSON.parse(response.data[0].form_questions);
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


            for (let i = 0; i < this.questions.length; i++) {
                if (document.getElementById('answer_' + this.questions[i].id).value == '') {
                    this.error = 1
                }
                this.questions[i].answer = document.getElementById('answer_' + this.questions[i].id).value;
            }

            if (this.error == 1) {
                toaster.error(`Заполните все поля`);
            } else {

                let data = {
                    id: this.id,
                    form_questions: JSON.stringify(this.questions),
                    status: 'close',
                };

                axios.post(`../../interview-worksheets/edit`, data)
                    .then(response => {
                        if (response.data.id) {
                            toaster.success(`Успешно`);
                            this.loading = false
                            this.editForm = false
                            setTimeout(() => {
                                window.close();
                            }, 1000)

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
