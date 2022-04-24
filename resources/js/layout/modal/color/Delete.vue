<template>
     <div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel3" aria-hidden="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Delete Color</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="text text-danger">
                    Bạn có chắc muốn xóa?
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" @click="submitDelete" data-dismiss="modal" class="btn btn-danger">Delete</button>
            </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions} from 'vuex'

export default {
    props: ['dataDelete'],
    methods: {
        ...mapActions(['deleteColor']),
        submitDelete(){
            this.deleteColor(this.dataDelete)
        }
    }
}
</script>