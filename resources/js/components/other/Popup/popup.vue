<template>
    <button class="btn  btn-primary mb-3" @click="openModal()">Создание контрагента</button>
    <div class="modal" tabindex="1" role="dialog" :style="{display:isOpenModal?'block':'none'}">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Создание контагента</h5>
                    <button @click="closeAllModal()" type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                        <form class="forms-sample">
                            <div class="form-group">
                                <label for="exampleInputUsername1">Username</label>
                                <input type="text" class="form-control" id="exampleInputUsername1" autocomplete="off" placeholder="Username">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Email address</label>
                                <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">Password</label>
                                <input type="password" class="form-control" id="exampleInputPassword1" autocomplete="off" placeholder="Password">
                            </div>
                        </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button  type="button" class="btn btn-primary">Save</button>
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Popup",
    data() {
        return {
            isOpenModal:false,
        }
    },
    props: {
        name: {
            type: String
        },
        btnname: {
            type: String
        },
        isOpen: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        openModal() {
            console.log('Открыто')
            this.isOpenModal = true
        },
        closeAllModal() {
            console.log('Закрыто')
            this.isOpenModal = false
        },
    },
}
</script>

<style scoped>
</style>
