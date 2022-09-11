<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div v-else>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Заказчик</label>
                        <input type="text" class="form-control" v-model="company" disabled>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Контрагент</label>
                        <input type="text" class="form-control" v-model="counterparty" disabled>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>НАИМЕНОВАНИЕ</label>
                <input type="text" class="form-control" v-model="name"
                       disabled placeholder="НАИМЕНОВАНИЕ">
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>СТОИМОСТЬ, РУБ </label>
                        <input type="number" min="0" class="form-control" v-model="price"
                               @input="calculationNds()" placeholder="СТОИМОСТЬ" v-if="!editField" disabled>
                        <input type="number" min="0" class="form-control" v-model="price"
                               @input="calculationNds()" placeholder="СТОИМОСТЬ" v-else>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>СТОИМОСТЬ, c УЧ. ВЕР-ТИ. РУБ </label>
                        <input type="number" min="0" class="form-control" id="price_probable" v-model="price_probable"
                               placeholder="СТОИМОСТЬ" v-if="!editField" disabled>

                        <input type="number" min="0" class="form-control" id="price_probable" v-model="price_probable"
                               placeholder="СТОИМОСТЬ" v-else>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>НДС, РУБ </label>
                        <input type="text" class="form-control" id="price_nds" v-model="price_nds"
                               placeholder="НДС" v-if="!editField" disabled>

                        <input type="text" class="form-control" v-model="price_nds"
                               placeholder="НДС" v-else>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label>Вероятность, %</label>
                        <input type="number" class="form-control" v-model="probability"
                               placeholder="Вероятность" min="0" max="100" v-if="!editField" disabled>

                        <input type="number" class="form-control" v-model="probability"
                               placeholder="Вероятность" min="0" max="100" v-else>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label>Тип контракта</label>
                        <select class="form-control" v-model="type_id" v-if="!editField" disabled>
                            <option value="0">Выберите тип контракта</option>
                            <option :value="item.id" v-for="item in contractType">{{ item.name }}</option>
                        </select>

                        <select class="form-control" v-model="type_id" v-else>
                            <option value="0">Выберите Статус контракта</option>
                            <option :value="item.id" v-for="item in contractType">{{ item.name }}</option>
                        </select>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label>Статус контракта</label>
                        <select name="contract_type" class="form-control" v-model="status_id" v-if="!editField" disabled>
                            <option value="0">Выберите статус контракта</option>
                            <option :value="item.id" v-for="item in contractStatus">{{ item.name }}</option>
                        </select>

                        <select name="contract_type" id="type_id" class="form-control" v-model="status_id" v-else>
                            <option value="0">Выберите статус контракта</option>
                            <option :value="item.id" v-for="item in contractStatus">{{ item.name }}</option>
                        </select>
                    </div>
                </div>

                <div class="col-6">
                    <div class="form-group">
                        <label>Статус этап контракта</label>
                        <select name="contract_type" class="form-control" v-model="stage_id" v-if="!editField" disabled>
                            <option value="0">Выберите этап контракта</option>
                            <option :value="item.id" v-for="item in contractStage">{{ item.name }}</option>
                        </select>

                        <select name="contract_type" class="form-control" v-model="stage_id" v-else>
                            <option value="0">Выберите этап контракта</option>
                            <option :value="item.id" v-for="item in contractStage">{{ item.name }}</option>
                        </select>
                    </div>
                </div>


            </div>

            <div class="form-group">
                <label>Наименование продукта</label>
                <input type="text" class="form-control" v-model="product_name" placeholder="Наименование продукта" v-if="!editField" disabled>
                <input type="text" class="form-control" v-model="product_name" placeholder="Наименование продукта" v-else>
            </div>


            <div class="form-group">
                <label>Описание</label>
                <textarea class="form-control" v-model="description" v-if="!editField" disabled></textarea>
                <textarea class="form-control" v-model="description" v-else></textarea>
            </div>
            <div class="form-group">
                <label>Текущие состояние</label>
                <textarea class="form-control" v-model="state_comment" v-if="!editField" disabled></textarea>
                <textarea class="form-control" v-model="state_comment" v-else></textarea>
            </div>

            <button type="button" class="btn btn-secondary" v-if="!editField" @click="changeEdit">Редактировать</button>
            <button type="button" class="btn btn-primary" v-else @click="save()">Сохранить</button>
            <slot name="footer"></slot>

        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {createToaster} from "@meforma/vue-toaster";
import inputSelect from '../other/selects/inputSelect';

export default {
    name: "contractEdit",
    components: {
        createToaster,
        inputSelect
    },
    data() {
        return {
            loading: false,

            editField: false,

            code: '', // Код контракта
            name: '', // Наименование контракта
            price_probable: 0, // СТОИМОСТЬ, c УЧ. ВЕР-ТИ. РУБ
            price: 0, // СТОИМОСТЬ, РУБ
            price_nds: 0, // НДС, РУБ
            probability: '', // Вероятность
            type_id: '', // Тип контракта
            description: '', // Описание контракта
            state_comment: '', // Описание состоятиня

            contractStatus: [], // Массив статусов контракта
            status_id: 0, // Статус контракта

            contractStage: [], // Массив этапов прокта
            stage_id: 0, // Этап прокта

            counterparties: [], // Массив контагенов
            counterparty_id: 0, // Выбранный контагент
            counterparty: '', // Выбранный контагент наименование

            companies: [], // Массив компаний
            company_id: 0, // Выбранная компания для кого создается контракт
            company: '', // Выбранная компания для кого создается контракт наименование

            contractType: [], // Массив типов контрактов

            contractAppointment: [], // Массив типо навправлений контрактов
            appointment_id: 0, // Выбраннное направлене

            product_name: '',

            info: '' // для теста
        }
    },
    props: {
        user_id: {
            type: Number
        },
        contract_id: {
            type: Number
        },

    },
    mounted() {
        this.loadCounterparties();
        this.loadContractStatus();
        this.loadContractStage();
        this.loadCompanyByUser();
        this.loadContractType();
        this.loadContractById();
    },
    methods: {

        changeEdit() {
            this.editField = true;
        },

        clear() {
            this.counterparty_id = 0;
            this.company_id = 0;
            this.name = '';
            this.price_probable = 0;
            this.price = 0;
            this.price_nds = 0;
            this.probability = 0;
            this.type_id = 0;
            this.state_comment = '';
            this.description = '';
            this.product_name = '';
            this.appointment_id  = 0;
        },

        searchCompanyName(id) {
            let obj = this.companies.find(el => {
                if (el.id == id) {
                    return el
                }
            });

            if (typeof obj == "undefined") {
                return {name: 'Загрузка...'}

            } else {
                return obj
            }


        },
        searchCounterpartiesName(id) {
            let obj = this.counterparties.find(el => {
                if (el.id == id) {
                    return el
                }
            });

            if (typeof obj == "undefined") {
                return {name: 'Загрузка...'}

            } else {
                return obj
            }

        },

        // Получение выбранного контрагента
        counterpartyEmit(id) {
            this.counterparty_id = id
        },

        // Получение выбранной компании
        companyEmit(id) {
            this.company_id = id
        },

        calculationNds() {
            return this.price_nds = Math.floor(this.price * 20 / 100)
        },

        // Получения спика компаний для данного юзера
        loadCompanyByUser() {
            axios
                .get(`../../companies/getByUser/${this.user_id}`)
                .then((response) => {
                    for (let key in response.data) {
                        this.companies.push(response.data[key]);
                    }
                })
        },

        // Получения спика статусов
        loadContractStatus() {
            axios
                .get(`../../contract/getStatus`)
                .then((response) => {
                    for (let key in response.data) {
                        this.contractStatus.push(response.data[key]);
                    }
                })
        },

        // Получения спика Этап
        loadContractStage() {
            axios
                .get(`../../contract/getStage`)
                .then((response) => {
                    for (let key in response.data) {
                        this.contractStage.push(response.data[key]);
                    }
                })
        },

        // Получение спика котрагентов
        loadCounterparties() {
            axios
                .get("../../manuals/counterparties/list")
                .then((response) => {
                    for (let key in response.data) {
                        this.counterparties.push(response.data[key]);
                    }
                })
        },

        // Получение Типов контрактов
        loadContractType() {
            axios
                .get("../../contract/getType")
                .then((response) => {
                    for (let key in response.data) {
                        this.contractType.push(response.data[key]);
                    }
                })
        },

        // Загрузка данных по контракту
        loadContractById() {
            this.loading = true
            axios
                .get(`../../contract/getContractById/${this.contract_id}`)
                .then((response) => {
                    this.loading = false;
                    this.name = response.data.name;
                    this.type_id = response.data.type_id;
                    this.price_probable = response.data.price_probable;
                    this.price = response.data.price;
                    this.price_nds = response.data.price_nds;
                    this.probability = response.data.probability;
                    this.state_comment = response.data.state_comment;
                    this.description = response.data.description;
                    this.company = this.searchCompanyName(response.data.company_id).name;
                    this.counterparty = this.searchCounterpartiesName(response.data.counterparty_id).name;
                    this.status_id = response.data.status_id;
                    this.stage_id = response.data.stage_id;
                    this.product_name = response.data.product_name;
                    this.appointment_id = response.data.appointment_id;
                })
        },


        // создание контракта
        save() {
            this.editField = false;
            const toaster = createToaster({position: 'top-right'});
            if (this.price == 0) {
                toaster.error(`СТОИМОСТЬ, РУБ не может быть "0"`);
            } else if (this.probable_nbs == 0) {
                toaster.error(`СТОИМОСТЬ, c УЧ. ВЕР-ТИ. РУБ не может быть "0"`);
            } else if (this.probability == 0) {
                toaster.error(`Вероятность, % не может быть "0 %"`);
            } else {
                this.loading = true
                let data = {
                    contract_id: this.contract_id,
                    price_probable: this.price_probable,
                    price: this.price,
                    price_nds: this.price_nds,
                    probability: this.probability,
                    state_comment: this.state_comment,
                    description: this.description,
                    status_id: this.status_id,
                    stage_id: this.stage_id,
                    appointment_id: this.appointment_id,
                    product_name: this.product_name
                }
                axios.post(`../../contract/update`, data)
                    .then(response => {
                        if (response.data) {
                            this.loading = false
                            // this.$parent.$refs.counterparties.load()
                            toaster.success(`Успешно`);
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
