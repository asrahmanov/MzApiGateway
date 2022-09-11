<template>

    <table class="table table-bordered" v-if="!loading">
        <thead>
        <tr>
            <th class="wordspace"><input type="text" v-model="last_name" @input="filter()" class="form-control" placeholder="Фамилия"></th>
            <th class="wordspace"><input type="text" v-model="first_name" @input="filter()" class="form-control" placeholder="Имя Отчество"></th>
            <th class="wordspace"><input type="text" v-model="job_title" @input="filter()" class="form-control" placeholder="Должность"></th>
            <th class="wordspace"><input type="text" v-model="email" @input="filter()" class="form-control" placeholder="email"></th>
            <th class="wordspace"><input type="text" v-model="phone" @input="filter()" class="form-control" placeholder="Телефон"></th>
            <th class="wordspace"><input type="text" v-model="role" @input="filter()" class="form-control" placeholder="Роль"></th>
            <th class="wordspace"><input type="text" v-model="departament" @input="filter()" class="form-control" placeholder="Департамент"></th>
            <th class="wordspace">Действие</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in filteredUsers">
            <td class="wordspace">{{ item.last_name }}</td>
            <td class="wordspace">{{ item.first_name }}</td>
            <td class="wordspace">{{ item.job_title }}</td>
            <td class="wordspace">{{ item.email }}</td>
            <td class="wordspace">{{ item.phone }}</td>
            <td class="wordspace">{{ item.role.name }}</td>
            <td class="wordspace">{{ item.departament.name }}</td>
            <td><a class="btn btn-success" :href="'../../users/info/' + item.id ">Открыть карточку</a></td>
        </tr>

        </tbody>
    </table>


    <div class="spinner-border text-primary" role="status" v-if="loading">
        <span class="sr-only">Loading...</span>
    </div>


</template>

<script>

import axios from 'axios';


export default {
    components: {},
    name: "UserView",

    props: [
        'company_id',
    ],
    data() {
        return {
            // Закртиые открытие редактирование полей
            editForm: false,
            //
            info: "",
            success: false,
            error: false,
            loading: false,

            users:[],
            filteredUsers:[],

            last_name: '',
            first_name: '',
            job_title: '',
            email: '',
            phone: '',
            role: '',
            departament: '',
        }
    },
    mounted() {
        this.loadInfo();
    },
    methods: {
        loadInfo() {

            this.filteredUsers = [];
            this.users = [];
            this.loading = true
            let data = {
                company_id: 1
            }
            axios.post(`../../users/list`, data)
                .then(response => {
                    this.info = response;
                    for (let key in response.data) {
                        this.users.push(response.data[key]);
                        this.filteredUsers.push(response.data[key]);
                    }
                    this.loading = false
                })
        },

        filter(type) {
            // if(type == 'last_name') {
            //     this.first_name = ''
            //     let regexp = new RegExp(this.last_name, 'i');
            //     this.filteredUsers = this.users.filter(el => regexp.test(el.last_name));
            // }
            //
            // if(type == 'first_name') {
            //     this.last_name = ''
            //     let regexp = new RegExp(this.first_name, 'i');
            //     this.filteredUsers = this.users.filter(el => regexp.test(el.first_name));
            // }

            let regexp_last_name = new RegExp(this.last_name, 'i');
            let regexp_first_name = new RegExp(this.first_name, 'i');
            let regexp_job_title = new RegExp(this.job_title, 'i');
            let regexp_phone = new RegExp(this.phone, 'i');
            let regexp_email = new RegExp(this.email, 'i');
            let regexp_role = new RegExp(this.role, 'i');
            let regexp_departament = new RegExp(this.departament, 'i');
            this.filteredUsers = this.users.filter(el => {
                if(regexp_first_name.test(el.first_name)
                    && regexp_last_name.test(el.last_name)
                    && regexp_job_title.test(el.job_title)
                    && regexp_phone.test(el.phone)
                    && regexp_email.test(el.email)
                    && regexp_role.test(el.role.name)
                    && regexp_departament.test(el.departament.name)
                ) {
                    return el
                }
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
