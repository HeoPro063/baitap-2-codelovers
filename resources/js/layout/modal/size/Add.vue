<template>
    <div>
        <div v-if="isActiveModalAddSize">
            <transition name="modal">
            <div class="modal-mask">
                <div class="modal-wapper">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Size</h5>
                        <button type="button" @click="CHANGE_ACTICE_MODAL_ADD_SIZE" class="close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="submitForm">
                        <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label for="disabledTextInput">Name Size</label>
                                    <input
                                        v-model="name"
                                        type="text"
                                        id="disabledTextInput"
                                        class="form-control"
                                        placeholder="enter name product"
                                        name="name"
                                    />
                                     <span
                                        v-if="!$v.name.required && $v.name.$dirty"
                                        class="text-danger"
                                    >Required field!</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" @click="CHANGE_ACTICE_MODAL_ADD_SIZE" class="btn btn-secondary">Close</button>
                            <button type="submit" class="btn btn-primary" >
                                 <div v-if="this.isStatusSize">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loding...
                                    </div>
                                    <div v-else>
                                        Add Size
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
    data() {
        return {
            name: "",
            status: false
        }
    },
    validations: {
        name: { required },
    },
    computed: {
        ...mapGetters(['isActiveModalAddSize', 'isStatusSize'])
    },
    methods: {
        ...mapActions(['postSize']),
        ...mapMutations(['CHANGE_ACTICE_MODAL_ADD_SIZE', 'CHANGE_STATUS_SIZE']),
        submitForm() {
            this.$v.$touch();
            if(!this.$v.$invalid) {
                this.CHANGE_STATUS_SIZE()
                var form = {
                    name: this.name
                }
                this.postSize(form)
                this.name = ''
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