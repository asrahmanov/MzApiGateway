<template>
    <div class="mb-5">
        <button type="submit" class="btn btn-primary mr-2" @click="viewForm = true " v-if="!viewForm">Добавить
            Пользователя
        </button>
        <button type="submit" class="btn btn-light mr-2" @click="viewForm = false" v-else>Отменить</button>
    </div>
    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>
    <div class="alert alert-fill-success" role="alert" v-if="success">
        Успешно
    </div>

    <div class="alert alert-fill-danger" role="alert" v-if="error!=''">
        {{ error }}!
    </div>
    <div v-if="viewForm">

        <div class="form-group">

            <label for="first_name">Имя Отчество</label>
            <input type="text" class="form-control" id="first_name" v-model="first_name" autocomplete="off"
                   placeholder="Имя Отчество"/>
        </div>
        <div class="form-group">
            <label for="last_name">Фамилия</label>
            <input type="text" class="form-control" id="last_name" placeholder="Фамилия" v-model="last_name">
        </div>

        <div class="form-group">
            <label for="job_title">Должность</label>
            <input type="text" class="form-control" id="job_title" autocomplete="off" placeholder="Должность"
                   v-model="job_title">
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control" id="email" autocomplete="off" placeholder="E-mail" v-model="email">
        </div>

        <div class="form-group">
            <label for="phone">Телефон</label>
            <input type="text" class="form-control" id="phone" placeholder="Телефон" v-model="phone">
        </div>

        <div class="form-group">
            <label for="password">Пароль</label>
            <input type="text" class="form-control" id="password" autocomplete="off" placeholder="Пароль"
                   v-model="password">
        </div>

        <div class="form-group">
            <label for="password_verified">Подтвердите пароль</label>
            <input type="text" class="form-control" id="password_verified" autocomplete="off" placeholder="Пароль"
                   v-model="password_verified">
        </div>

        <div class="form-group">
            <label for="user_company_id">Выберите компанию </label>
            <select name="" id="user_company_id" class="form-control" v-model="user_company_id">
                <option value="0">Выберите компанию</option>
                <option v-for="item in filteredCompany" :value="item.id">{{ item.name }}</option>
            </select>
        </div>


        <button type="submit" class="btn btn-success mr-2" @click="Save()">Создать</button>
    </div>

</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "Useradd",
    props: [
        'company_id',
    ],
    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            //
            success: false,
            error: '',
            loading: false,
            viewForm: false,

            //  Данные формы
            first_name: '',
            last_name: '',
            job_title: '',
            email: '',
            phone: '',
            password: '',
            password_verified: '',

            //
            user_company_id: 0,
            filteredCompany: [],
            company: [],

        }
    },
    mounted() {
        this.loadCompany();
    },
    methods: {

        validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        },

        Save() {
            const toaster = createToaster({position: 'top-right'});
            if (this.first_name == '') {
                toaster.error(`Заполните Имя Отчество`);
            } else if (this.last_name == '') {
                toaster.error(`Заполните поле Фамилия`);
            } else if (this.job_title == '') {
                toaster.error(`Заполните поле Должность`);
            } else if (this.validateEmail(this.email) != true) {
                toaster.error(`Поля email заполен не верно`);
            } else if (this.phone == '') {
                toaster.error(`Заполните поле телефон`);
            } else if (this.password == '') {
                toaster.error(`Пароль не может быть пустым`);
            } else if (this.password != this.password_verified) {
                toaster.error(`Пароли не совпадают`);
            } else if (this.user_company_id == 0) {
                toaster.error(`Выберите компанию`);
            } else {
                this.loading = true
                let data = {
                    first_name: this.first_name,
                    job_title: this.job_title,
                    email: this.email,
                    phone: this.phone,
                    password: this.password,
                    user_company_id: this.user_company_id,
                    role_id: 2,
                }
                axios.post(`../users/add`, data)
                    .then(response => {
                        if (response.data.result) {
                            toaster.success(`Успешно`);
                            this.loading = false
                            this.viewForm = false
                            this.$parent.$refs.UserList.loadInfo()

                            this.first_name = '';
                            this.last_name = '';
                            this.job_title = '';
                            this.email = '';
                            this.phone = '';
                            this.password = '';
                            this.user_company_id = 0;
                            this.password_verified = '';

                        } else {
                            if(response.data.errors){
                                toaster.error(response.data.errors);
                            } else {
                                toaster.error('Ошибка сервера');
                            }
                            this.loading = false
                        }
                    })

            }
        },

        loadCompany() {
            this.filteredCompany = [];
            this.company = [];
            this.loading = true
            let data = {
                company_id: this.company_id
            }
            axios.post(`../companies/parentList`, data)
                .then(response => {
                    for (let key in response.data) {
                        this.company.push(response.data[key]);
                        this.filteredCompany.push(response.data[key]);
                    }
                    this.loading = false
                })
        }

    },
    computed: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
