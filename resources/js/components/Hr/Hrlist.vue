<template>
    <h4>Список пользоватлей</h4>
    <table class="table table-bordered" v-if="!loading">
        <thead>
        <tr>
            <th>Фамилия</th>
            <th>Имя, Очество</th>
            <th>Должность</th>
            <th>email</th>
            <th>Телефон</th>
            <th>Роль</th>
            <th>Компания</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="item in filteredUsers">
            <td>{{ item.last_name }}</td>
            <td>{{ item.first_name }}</td>
            <td>{{ item.job_title }}</td>
            <td>{{ item.email }}</td>
            <td>{{ item.phone }}</td>
            <td>{{ item.role.name }}</td>
            <td><span v-for="company in item.company"> {{ company.name }}<br></span></td>
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
    name: "Userlist",
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
                company_id: this.company_id
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

    },
    computed: {}

}
</script>

<style scoped>
.nav-item {
    cursor: pointer;
}
</style>
