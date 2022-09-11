<template>

    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>

    <div class="alert alert-fill-success" role="alert" v-if="success">
        Успешно
    </div>

    <div class="alert alert-fill-danger" role="alert" v-if="error!=''">
        {{ error }}!
    </div>
    <div>

        <div class="form-group">

            <label>Имя Отчество</label>
            <input type="text" class="form-control"  v-model="first_name" autocomplete="off"
                   placeholder="Имя Отчество" v-if="!editForm" disabled/>
            <input type="text" class="form-control"  v-model="first_name" @input="event => first_name = validateStr(event.target.value)" autocomplete="off"
                   placeholder="Имя Отчество" v-else/>
        </div>
        <div class="form-group">
            <label for="last_name">Фамилия</label>
            <input type="text" class="form-control"  placeholder="Фамилия" v-model="last_name"
                   v-if="!editForm" disabled>
            <input type="text" class="form-control"  placeholder="Фамилия" v-model="last_name" v-else @input="event => last_name = validateStr(event.target.value)">
        </div>

        <div class="form-group">
            <label for="job_title">Должность</label>
            <input type="text" class="form-control"  autocomplete="off" placeholder="Должность"
                   v-if="!editForm" disabled
                   v-model="job_title">
            <input type="text" class="form-control"  autocomplete="off" placeholder="Должность" v-else
                   v-model="job_title" @input="event => job_title = validateStr(event.target.value)">
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" class="form-control"  autocomplete="off" placeholder="E-mail" v-model="email"
                   disabled v-if="!editForm">
            <input type="email" class="form-control"  autocomplete="off" placeholder="E-mail" v-model="email"
                   v-else>
        </div>

        <div class="form-group">
            <label>Телефон</label>
            <input type="text" class="form-control"    placeholder="Телефон" v-model="phone" v-if="!editForm"
                   disabled/>
            <input type="text" class="form-control"  placeholder="Телефон" v-model="phone" @input="event => phone = validatePhone(event.target.value)"  v-else/>
        </div>

        <div class="form-group">
            <label>Выберите Роль </label>
            <select class="form-control" v-model="role_id" v-if="!editForm" disabled>
                <option value="0">Выберите роль</option>
                <option v-for="item in roles" :value="item.id">{{ item.name }}</option>
            </select>

            <select class="form-control" v-model="role_id" v-else>
                <option value="0">Выберите роль</option>
                <option v-for="item in roles" :value="item.id">{{ item.name }}</option>
            </select>
        </div>

        <div class="form-group">
            <label >Введите новый пароль ( если хотите его изменить )</label>
            <input type="text" class="form-control"  autocomplete="off" placeholder="Пароль"
                   v-model="password" v-if="!editForm" disabled>
            <input type="text" class="form-control"  autocomplete="off" placeholder="Пароль"
                   v-model="password" v-else>
        </div>

        <div class="form-group">
            <label >Подтвердите пароль</label>
            <input type="text" class="form-control"  autocomplete="off" placeholder="Пароль"
                   v-model="password_verified" v-if="!editForm" disabled>
            <input type="text" class="form-control"  autocomplete="off" placeholder="Пароль"
                   v-model="password_verified" v-else>
        </div>


        <button type="button" class="btn btn-primary mr-2" @click="editForm = !editForm " v-show="!editForm">
            Редактировать
        </button>
        <button type="button" class="btn btn-primary mr-2" @click="editForm = !editForm " v-show="editForm">Отменить
        </button>
        <button type="button" class="btn btn-success mr-2" @click="Save()" v-show="editForm">Сохранить</button>
    </div>

</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "UserUpdate",
    props: [
        'user_id',
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
            role_id: 1,
            departament_id: 1,
            id: 0,
            password: '',
            password_verified: '',

            //
            user_company_id: 0,
            filteredCompany: [],
            roles: [],
            user: {},
            company: [],

        }
    },
    mounted() {
        this.loadUser()
        this.loadRoles()
    },
    methods: {

        validateEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)
        },

        validateStr(str){
           return  str.replace(/[^А-яЁё ]/g,"")
        },
        validatePhone(phone){
            let phone_v = phone.replace(/[^\+\d]/g, '');
            if (phone_v.length > 12) {
                phone_v = phone_v.substr(0, 12)
            }
           return phone_v
        },


        loadUser() {
            this.loading = true;
            axios.get(`../../users/${this.user_id}`)
                .then(response => {
                    this.user = response.data[0];
                    this.loading = false
                    this.viewForm = false
                    this.first_name = this.user.first_name
                    this.last_name = this.user.last_name
                    this.job_title = this.user.job_title
                    this.email = this.user.email
                    this.phone = this.user.phone
                    this.role_id = this.user.role_id
                    this.departament_id = this.user.departament_id
                    this.id = this.user.id
                })
        },

        loadRoles() {
            axios.get(`../../users/getRole`)
                .then(response => {
                    this.roles = response.data;
                })
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
            } else if (this.password != this.password_verified) {
                toaster.error(`Пароли не совпадают`);
            } else {
                this.loading = true
                this.editForm = false
                let data;
                if (this.password == '') {
                    data = {
                        user_id: this.user_id,
                        first_name: this.first_name,
                        last_name: this.last_name,
                        job_title: this.job_title,
                        email: this.email,
                        phone: this.phone,
                        role_id: this.role_id,
                    }
                } else {
                    data = {
                        user_id: this.user_id,
                        first_name: this.first_name,
                        last_name: this.last_name,
                        job_title: this.job_title,
                        email: this.email,
                        phone: this.phone,
                        role_id: this.role_id,
                        password: this.password,
                    }
                }

                axios.post(`../../users/edit`, data)
                    .then(response => {
                        if (response.data.result) {
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
    watch: {
        phone: function(phone) {
            if (phone.length < 2) {
                this.phone = '+7';
            }
        }
    }

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
