<template>
    <div class="form-group">
        <label for="inn">ИНН</label>
        <input type="text" class="form-control" id="inn" v-model="inn"
               placeholder="ИНН">
    </div>
    <div class="form-group">
        <label for="companyname">НАИМЕНОВАНИЕ</label>
        <input type="text" class="form-control" id="companyname" v-model="companyname"
               placeholder="НАИМЕНОВАНИЕ">
    </div>
    <div class="form-group">
        <label for="phone">ТЕЛЕФОН</label>
        <input type="text" class="form-control" id="phone" v-model="phone"
               placeholder="ТЕЛЕФОН">
    </div>
    <div class="form-group">
        <label for="description">ОПИСАНИЕ</label>
        <input type="text" class="form-control" id="description" v-model="description"
               placeholder="ОПИСАНИЕ">
    </div>

    <button type="button" class="btn btn-primary" @click="checkInn()">Создать</button>

</template>

<script>
import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    name: "counterpartiescreate",
    components: {
        createToaster
    },
    data() {
        return {
            isOpenModal: false,
            loading: false,
            inn: '',
            companyname: '',
            phone: '',
            description: '',
            info: ''

        }
    },
    props: {
        name: {
            type: String
        },
    },
    methods: {
        openModal() {
            this.isOpenModal = true;
        },
        closeAllModal() {
            this.isOpenModal = false;
            this.clear();
        },
        clear() {
            this.inn = '';
            this.companyname = '';
            this.phone = '';
            this.description = '';
        },
        checkInn(inn) {
            const toaster = createToaster({position: 'top-right'});
            axios.post(`../counterparties/checkinn/${this.inn}`)
                .then(response => {
                    if (response.data.id > 0) {
                        toaster.info(`Компания с таким ИНН уже зарегестрирована`);
                    } else {
                        this.create();
                    }

                })
        },


        create() {
            const toaster = createToaster({position: 'top-right'});
            if (this.inn.length != 10) {
                toaster.error(`Заполните поле ИНН (10 знаков)`);
            } else if (this.companyname == '') {
                toaster.error(`Заполните поле Наименование`);
            } else {
                this.loading = true
                let data = {
                    inn: this.inn,
                    name: this.companyname,
                    phone: this.phone,
                    description: this.description
                }
                axios.post(`../counterparties/add`, data)
                    .then(response => {
                        this.info = response;
                        if (response.data.id > 0) {

                            this.loading = false
                            this.closeAllModal()
                            // this.$parent.$refs.counterparties.load()

                            toaster.success(`Успешно`);
                            this.clear();


                        } else {
                            toaster.error(`Ошибка сервера`);
                            this.loading = false

                        }

                    })

            }
        }
    }
}
</script>

<style scoped>
</style>
