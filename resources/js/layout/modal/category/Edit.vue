<template>
    <div>
        <div v-if="isActiveModalEditCategory">
            <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wapper">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" @click="CHANGE_ACTICE_MODAL_EDIT_CATEGORY" class="close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitForm">
                        <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label for="disabledTextInput">Name Category</label>
                                    <input
                                        v-model="dataEdit.name"
                                        type="text"
                                        id="disabledTextInput"
                                        class="form-control"
                                        placeholder="enter name product"
                                        name="name"
                                    />
                                     <span
                                        v-if="!$v.dataEdit.name.required && $v.dataEdit.name.$dirty"
                                        class="text-danger"
                                    >Required field!</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="CHANGE_ACTICE_MODAL_EDIT_CATEGORY" class="btn btn-secondary">Close</button>
                            <button type="submit" class="btn btn-primary" >
                                 <div v-if="this.isStatusCategory">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loding...
                                    </div>
                                    <div v-else>
                                        Add Color
                                    </div>
                            </button>
                        </div>
                    </form>
                    </div>
                </div>
                </div>
            </div>
            </transition>
        </div>
    </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";
import { mapGetters, mapActions, mapMutations } from "vuex";

export default {
    props: ['dataEdit'],
    data() {
        return {
            status: false
        }
    },
    validations: {
        dataEdit: {
            name: {required}
        }
    },
    computed: {
        ...mapGetters(['isActiveModalEditCategory', 'isStatusCategory'])
    },
    methods: {
        ...mapActions(['editCategory']),
        ...mapMutations(['CHANGE_ACTICE_MODAL_EDIT_CATEGORY', 'CHANGE_STATUS_CATEGORY']),
        submitForm() {
            this.$v.$touch();
            if(!this.$v.$invalid) {
                this.CHANGE_STATUS_CATEGORY()
                this.editCategory(this.dataEdit)
                this.$nextTick(() => { this.$v.$reset() })
            }
        }
    }
}
</script>

<style scoped>

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: table;
  transition: 0.6s ease-in-out;
}

.modal-wapper {
  direction: table-cell;
  vertical-align: middle;
}

</style>