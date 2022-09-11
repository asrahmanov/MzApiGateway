<template>
    <div class="v-red-block">
        <button class="v-red-button" @click = "deleteAll" v-if="user_id===1">
            Снести все
        </button>
        <p v-else class="v-red-text">
            У вас нет такого доступа
        </p>
        <p v-if="loading">Идет удаление...</p>
    </div>
</template>

<script>

import axios from 'axios';

export default {
    name: "RedButton",
    props: [
        'user_id',
    ],
    data() {
        return {
            loading: false,
        }
    },
    mounted() {
    },
    methods: {
        deleteAll(){
            let url = "/red-button";
            this.loading = true;
            axios
                .post(url)
                .then((response) => {
                    console.log('response', response)
                    this.loading=false
                })
        }
    },
    computed: {}

}
</script>

<style scoped>
.v-red-block{
    display: flex;
    justify-content: center;
}
.v-red-button{
    border-radius: 60%;
    padding: 59px 25px;
    font-size: 18px;
    background: red;
}
.v-red-text{

}
</style>
