<template>

    <button class="btn btn-primary mb-3" style="margin-top: 3px;" @click="openModal()">+</button>
    <div class="modal" tabindex="1" role="dialog" :style="{display:isOpenModal?'block':'none'}">
        <div class="modal-dialog" role="document">
            <div class="modal-content boxshadow">
                <div class="modal-header">
                    <h5 class="modal-title">Создание контагента</h5>
                    <button @click="closeAllModal()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="spinner-border text-primary" role="status" v-if="loading">
                        <span class="sr-only">Loading...</span>
                    </div>

                    <div v-else>
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
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeAllModal()">
                        Отменить
                    </button>
                    <button type="button" class="btn btn-primary" @click="checkInn()">Создать</button>
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";

export default {
    name: "modalcreate",
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
            axios.post(`../../manuals/counterparties/checkinn/${this.inn}`)
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
            if (this.inn.length != 11) {
                toaster.error(`Заполните поле ИНН (11 знаков)`);
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
                axios.post(`../../manuals/counterparties/add`, data)
                    .then(response => {
                        this.info = response;
                        if (response.data.id > 0) {

                            this.loading = false
                            this.closeAllModal()
                            if (this.$parent.$refs.counterparties) {
                                this.$parent.$refs.coun
                            }

                            if (this.$parent.loadCounterparties) {
                                this.$parent.loadCounterparties()
                            }


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
