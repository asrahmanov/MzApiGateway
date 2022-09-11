<template>
    <div class=" v-input-select">
        <label class="label-big">{{ title }} </label>
        <input class="form-control" @focus="isOpenOptions=true" @change.stop  type="text" v-model="searchTerm" :disabled="disabled">
        <i v-if="searchTerm.length" @click="clear" class="mdi mdi-close v-input-select__clear-input"></i>
        <div v-show="isOpenOptions" class="v-input-select__options-box">
            <p class="v-input-select__options-item" @click="select(item)" @change.stop v-for="(item,idx) in searchOptions">{{item[nameField]}}</p>
        </div>
    </div>
</template>

<script>

export default {
    name: "InputSelect",
    props:{
        title:{
            type:String,
            default:'Input-select'
        },
        options:{
            type:Array,
            default: []
        },
        idField:{
            type:String,
            default:'id'
        },
        nameField:{
            type:String,
            default:'name'
        },
        selectedId:{
            default:null
        },
        disabled:{
            type:Boolean,
            default:false
        },
    },
    data(){return{
        searchTerm:'',
        isOpenOptions:false,
    }},
    created() {
        //закрытие листа при клике вне области компонента
        document.addEventListener('click', e=> {
            if (!this.$el.contains(e.target) && this.isOpenOptions){
                this.isOpenOptions = false;
            }
        });
    },
    mounted() {
        if (this.selectedId){
            this.searchTerm = this.options.find(e=>{
                return  e[this.idField] == this.selectedId
            })[this.nameField]
        }
    },
    methods:{
        select(item){
            this.searchTerm = item[this.nameField]
            this.$emit('change',item[this.idField])
            this.isOpenOptions=false
        },
        clear(){
            this.searchTerm = ''
            this.$emit('change','')
            this.isOpenOptions=false
        }
    },
    computed:{
        searchOptions(){
            if (this.searchTerm.length){
                return this.options.filter(e=>{
                    try {
                        return  e[this.nameField] && e[this.nameField].toLowerCase().includes(this.searchTerm.toLowerCase())
                    }catch (err){
                        console.log('errElem',e,'err',err)
                    }

                })
            }else {
                return this.options
            }

        }
    }
}
</script>

<style lang="scss" scoped>
.v-input-select{
    position: relative;
    &__clear-input{
        position: absolute;
        bottom: 6px;
        right: 10px;
        cursor: pointer;
        &:hover{
            color: orangered;
        }
    }
}
.v-input-select__options-box{
    border: 1px solid #dddcdc;
    position: absolute;
    z-index: 1;
    background-color: white;
    width: 100%;
    overflow: auto;
    height: 200px;
}
.v-input-select__options-item{
    padding: 5px;
    cursor: pointer;
    &:hover{
        background-color: #727cf5;
        color: white;
    }
}
.form-control{
    border: 1px solid #c9ccd1;
}
.label-big{
    font-weight: 600;
    line-height: 1.2;
    margin-bottom: 0;
    font-size: 1.2rem;
}
.tooltip{
    font-size: 50px!important;
}
</style>
