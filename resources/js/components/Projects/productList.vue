<template>
    <div>
        <div class="spinner-border text-primary" role="status" v-if="loading">
            <span class="sr-only">Loading...</span>
        </div>

        <div v-else style="width: 100%; overflow-x: visible;">

        <table class="table table-bordered" id="datatable" >
            <thead>
            <tr>
                <th>Название продукта</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in projectsFilter" :key="item.id">
                <td v-if="item.product_name"> <a :href="'/project/productReport/'+ item.product_name" target="_blank">{{item.product_name }}</a></td>
            </tr>
            </tbody>
        </table>
        </div>
    </div>
</template>

<script>

import axios from 'axios';

export default {
    name: 'productList',

    components: {

    },
    props: {
        user_id: {
            type: Number
        },
    },
    computed: {

    },
    methods: {


        btnStyles(stage_id) {
                let width = (20 * stage_id) + 1 + '%';
                return {
                    width: width
                }
        },
        load() {
            this.loading = true;

            let url;
            let urlContracts;
            if(this.user_id === 1) {
                url = "../project/getProjectAll";
                urlContracts = "../contract/getContractAll";
            } else {
                url = `../project/getProjectByUser/${this.user_id}`;
                urlContracts = `../contract/getContractByUser/${this.user_id}`;
            }

                axios
                    .get(url)
                    .then((response) => {
                        this.info = response;
                        response.data.forEach(project => {
                            project.type = 'project'
                            project.typeName = 'Проект'
                            let index = this.projectsFilter.findIndex(item=>item.product_name === project.product_name)
                            if(index === -1){
                                this.projectsFilter.push(project)
                            }
                        })
                        if(this.typeReport === 'Projects'){
                            this.loading = false
                        }
                    })
                axios
                    .get(urlContracts)
                    .then((response) => {
                        //this.info = response;
                        //console.log('Контракты', response.data)
                        response.data.forEach(contract=>{
                            contract.type = 'contract'
                            contract.typeName = 'Контракт'
                            let index = this.projectsFilter.findIndex(item=>item.product_name === contract.product_name)
                            if(index === -1){
                                this.projectsFilter.push(contract)
                            }
                        })
                        this.loading = false
                    })
        },

    },
    mounted() {
        this.load();
    },
    data: function () {
        return {
            projects: [],
            projectsFilter: [],
            info: '',
            loading: false,
        }
    },
}
</script>
<style>
.v-total-top{
    border-top: 2px solid black;
}
</style>
