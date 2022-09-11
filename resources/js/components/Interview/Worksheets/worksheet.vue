<template>


    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>


    <div class="col-12" v-show="!loading">

        <table class="vtl-table table table-hover table-bordered table-responsive-sm" id="table">
            <thead>
            <tr>
                <th class="wordspace">Выберите форму</th>
                <th class="wordspace" >Выберите дзо</th>
                <th class="wordspace">Выберите сотрудника</th>
                <th class="wordspace">Действие</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td class="wordspace">
                    <select class="form-control" v-model="form_id">
                        <option value="0">Выберите форму</option>
                        <option :value="item.id" v-for="item in report">{{ item.name }}</option>
                    </select>
                </td>

                <td class="wordspace" >
                    <select class="form-control" v-model="dzo_id" @change="filterUsers()">
                        <option value="0">Все ДЗО</option>
                        <option :value="item.id" v-for="item in company">{{ item.name }}</option>
                    </select>
                </td>


                <td class="wordspace">
                    <select class="form-control" v-model="user_id">
                        <option value="0">Все сотрудники</option>
                        <option :value="item.id" v-for="item in filteredUsers">{{ item.last_name }} {{
                                item.first_name
                            }} - {{ item.company[0].name }}
                        </option>
                    </select>

                </td>

                <td class="wordspace">
                    <button class="btn btn-success" @click="save()" v-show="!loadingBtn">Создать задание</button>
                    <div class="spinner-border" role="status" v-show="loadingBtn">
                        <span class="sr-only">Loading...</span>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>

<!--        form_id {{ form_id }}<br>-->
<!--        dzo_id {{ dzo_id }}<br>-->
<!--        user_id {{ user_id }}<br>-->

    </div>
</template>


<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "worksheet",

    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            loadingBtn: false,
            loading: false,

            company_id: 17,
            plan_of_transfer_to_otk: 0,

            filteredCompany: [],
            company: [],
            report: [],
            filteredreport: [],

            filteredUsers: [],
            users: [],

            user_id: 0,
            form_id: 0,
            dzo_id: 0,


        }
    },
    mounted() {
        this.loadUsers()
        // this.loadCompany();
        this.loadForm();
    },
    methods: {

        filterUsers() {
            this.user_id = 0;
            if (this.dzo_id == 0) {
                this.filteredUsers = this.users
            } else {
                this.filteredUsers = this.users.filter(el => {
                    if (el.company[0].id == this.dzo_id) {
                        return true;
                    }
                })
            }

        },

        // loadCompany() {
        //     this.filteredCompany = [];
        //     this.company = [];
        //     let data = {
        //         company_id: 1
        //     }
        //     axios
        //         .get(`../../companies/getByUser/${this.user_id}`)
        //         .then((response) => {
        //             this.company = response.data;
        //             this.filteredCompany = response.data;
        //         })
        // },


        loadForm() {
            this.loading = true
            this.report = [];
            axios
                .get(`../../interview-form`)
                .then((response) => {
                    this.report = response.data;
                    this.filteredreport = this.report;
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
                        this.company.push(response.data[key].company[0]);
                    }
                    this.loading = false
                }).then(() => {
            })
        },

        getUserName(user_id) {
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
                    email: ''
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


        getForm(form_id) {
            return this.report.filter(el => {
                if (el.id == form_id) {
                    return true;
                }
            })
        },

        mailSend(subject, text, email) {
            let data =  {
                email,
                subject,
                text
            }

            axios.post(`../../mail/send`, data)
                .then(response => {
                        // toaster.success(`Письмо отправлено`);
                    console.log(response);
                })


        },


        save() {
            const toaster = createToaster({position: 'top-right'});

            this.loadingBtn = true
            if (this.form_id == 0) {
                toaster.error(`Выберите форму`);
            } else {

                if(this.user_id == 0) {

                    for(let i = 0; i < this.users.length; i++) {

                        let data = {
                            user_id: this.users[i].id,
                            company_id: this.users[i].company[0].id,
                            name: this.getForm(this.form_id)[0].name,
                            form_questions: this.getForm(this.form_id)[0].form,
                            form_answers: this.getForm(this.form_id)[0].form,
                            status: "new",
                        }

                        axios.post(`../../interview-worksheets/create`, data)
                            .then(response => {
                                if (response.data.id) {
                                    toaster.success(`Успешно`);
                                    let subject = data.name;
                                    let text = `Добрый день, требутеся заполнить форму ${subject}`
                                    let email = this.users[i].email
                                    this.mailSend(subject, text, email);
                                }
                                this.loadingBtn = false
                            })

                    }


                } else {
                    let data = {
                        user_id: this.user_id,
                        company_id: this.dzo_id,
                        name: this.getForm(this.form_id)[0].name,
                        form_questions: this.getForm(this.form_id)[0].form,
                        form_answers: this.getForm(this.form_id)[0].form,
                        status: "new",
                    }

                    axios.post(`../../interview-worksheets/create`, data)
                        .then(response => {
                            if (response.data.id) {
                                toaster.success(`Успешно`);
                                let subject = data.name;
                                let text = `Добрый день, требутеся заполнить форму ${subject}`
                                let email = this.getUserName(this.user_id).email
                                this.mailSend(subject, text, email);
                            }
                            this.loadingBtn = false
                        })


                }



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
