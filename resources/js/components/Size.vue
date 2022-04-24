<template>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header justify-content-between">
                        <div class="container">
                        <div class="row">
                            <div class="col-md-8">
                            <h3 class="card-title cursor-button">Size</h3>
                            </div>
                            <!-- <div class="col-md-2">
                            <div class="input-group">
                                <div class="form-outline">
                                <input
                                    type="text"
                                    id="form1"
                                    class="form-control"
                                    placeholder="Enter search..."
                                />
                                </div>
                            </div>
                            </div>
                            <div class="col-md-2">
                            <button
                                type="button"
                                class="btn btn-primary"
                            >Tìm kiếm</button>
                            </div> -->
                            <div class="col-md-2">
                            <button
                                type="button"
                                class="btn btn-primary"
                                data-toggle="modal"
                                data-target="#exampleModal"
                                @click="CHANGE_ACTICE_MODAL_ADD_SIZE"
                            >New Size</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                            <th style="width: 10px">#</th>
                            <th style="width: 70%">Name Color</th>
                            <th style="width: 25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in isListSize" :key="item.id">
                                <td>{{stt(index)}}</td>
                                <td>{{item.name}}</td>
                                <td>
                                    <button
                                    type="button"
                                    class="btn btn-secondary"
                                    @click="clickEditSize(item, index)"
                                    >Edit</button>
                                    <button
                                    class="btn btn-danger"
                                    data-toggle="modal"
                                    data-target="#exampleModal3"
                                     @click="clickDeleteSize(item.id, index)"
                                    >Delete</button>
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    
                    </div>
                </div>
            </div>
        <AddModal />
        <EditModal :dataEdit="dataEdit"/>
        <DeleteModal :dataDelete="dataDelete"/>
        </div>


</template>

<script>
import { mapGetters, mapActions, mapMutations} from "vuex";
import AddModal from "../layout/modal/size/Add.vue";
import EditModal from "../layout/modal/size/Edit.vue";
import DeleteModal from "../layout/modal/size/Delete.vue";

export default {
    created() {
        this.getSize()
    },
    data() {
        return {
            dataEdit: {
                id: '',
                name: '',
                created_at: '',
                updated_at: '',
                index: ''
            },
            dataDelete: {
                id: '',
                index: ''
            }
        }
    },
    computed: {
        ...mapGetters(['isListSize', 'isActiveModalAddSize'])
    },
    methods: {
        ...mapActions(['getSize']),
        ...mapMutations(['CHANGE_ACTICE_MODAL_ADD_SIZE', 'CHANGE_ACTICE_MODAL_EDIT_SIZE']),
        stt(number) {
            return number + 1;
        },
        clickEditSize(item, index) {
            this.dataEdit.id =  item.id
            this.dataEdit.name =  item.name
            this.dataEdit.created_at =  item.created_at
            this.dataEdit.upadted_at =  item.upadted_at
            this.dataEdit.index =  index
            this.CHANGE_ACTICE_MODAL_EDIT_SIZE()
        },
        clickDeleteSize(id, index) {
            this.dataDelete.id = id
            this.dataDelete.index = index

        }
    },
    components: {
        AddModal,
        EditModal,
        DeleteModal
    }
    
}
</script>

<style>

</style>