<template>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="text" class="form-control" placeholder="Name category" v-model="form.name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" @click="saveCategory" data-dismiss="modal" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>
</template>

<script>

import {mapActions, mapMutations} from 'vuex'
export default {
    data(){
        return {
            doSometing: false,
            form: {
                name: ''
            }
        }
    },
    methods: {
        ...mapActions(['addCategory']),
        ...mapMutations(['GET_ALERT']),
        saveCategory() {
            if(this.form.name == ''){
               return this.GET_ALERT(true)
            }
            this.addCategory(this.form)
            this.form.name = ''
            this.GET_ALERT(false)
        }
    }
}
</script>