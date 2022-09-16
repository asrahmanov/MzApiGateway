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
                <label>Login</label>
                <input name="login" type="text" class="form-control" v-model="login" placeholder="Login" >
            </div>
            <div class="form-group">
                <label>Password</label>
                <input name="password" type="password" class="form-control"  v-model="password" placeholder="Password" required>
            </div>
            <div class="mt-3">
                <button type="button"  class="btn btn-primary mr-2 mb-2 mb-md-0" @click="auth()">Login</button>
            </div>
    </div>

</template>

<script>

import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    components: {
        createToaster
    },
    name: "auth",

    data() {
        return {
            editForm: false,
            success: false,
            error: '',
            loading: false,
            viewForm: false,

            //  Данные формы
            login: '',
            password: '',
        }
    },
    mounted() {
    },
    methods: {


        auth() {
            const toaster = createToaster({position: 'top-right'});
            if (this.login == '') {
                toaster.error(`Заполните логин`);
            } else if (this.last_name == '') {
                toaster.error(`Заполните пароль`);
            } else {
                this.loading = true
                let data = {
                    login: this.login,
                    password: this.password
                }
                axios.get(`../users/login/${this.login}/${this.password}`)
                    .then(response => {
                        console.log(response.data);
                        if(response.data == true) {
                            toaster.success("Успешно");
                            location.href = "../../"
                        } else {
                            toaster.error(response.data)
                        }
                            this.loading = false
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
