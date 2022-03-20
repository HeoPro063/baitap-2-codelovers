<template>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="" v-model="dataedit.id">
                <input type="text" class="form-control" placeholder="Name category"  v-model="dataedit.name">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" @click="saveEdit" data-dismiss="modal" class="btn btn-primary">Save changes</button>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import {mapGetters,mapActions} from 'vuex'

export default {
    props: ['dataedit'],
    computed: {
        ...mapGetters(['isShowCategory'])
    },
    methods: {
        ...mapActions(['editCategory']),
        saveEdit(){
            this.editCategory(this.dataedit)
        }
    }
}
</script>