<template>
  <div v-if="isActiveModalUpdate">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wapper">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Update Product</h5>
                <button type="button" class="close" @click="CHANGE_ACTICE_MODAL_UPDATE">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="submitForm" enctype="multipart/form-data">
                <div class="modal-body">
                  <div class="container-fluid">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="disabledTextInput">Name Category</label>
                          <div>
                            <input type="hidden" v-model="dataEdit.id">
                            <select v-model="dataEdit.category_id" class="custom-select">
                              <option
                                v-for="item  in islistCategories"
                                :key="item.id"
                                :value="item.id"
                              >{{item.name}}</option>
                            </select>
                          </div>
                          <span
                            v-if="!$v.dataEdit.category_id.required && $v.categoryId.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Name Product</label>
                          <input
                            v-model="dataEdit.name"
                            type="text"
                            id="disabledTextInput"
                            class="form-control"
                            placeholder="enter name product"
                            name="name"
                          />
                          <span
                            v-if="!$v.dataEdit.name.required && $v.name.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Image</label>
                          <div class="custom-file">
                            <input
                              type="file"
                              class="custom-file-input"
                              id="inputGroupFile01"
                              @change="onChangeImg"
                            />
                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="disabledTextInput">Price</label>
                          <input
                            v-model="dataEdit.price"
                            type="text"
                            id="disabledTextInput"
                            class="form-control"
                            placeholder="enter name product"
                          />
                          <span
                            v-if="!$v.dataEdit.price.required && $v.price.$dirty"
                            class="text-danger"
                          >Required field!</span>
                          <span
                            v-if="!$v.dataEdit.price.numeric && $v.price.$dirty"
                            class="text-danger"
                          >Numeric field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Color</label>
                          <input
                            v-model="dataEdit.color"
                            type="text"
                            id="disabledTextInput"
                            class="form-control"
                            placeholder="enter name product"
                          />
                          <span
                            v-if="!$v.dataEdit.color.required && $v.color.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Size</label>
                          <input
                            v-model="dataEdit.size"
                            type="text"
                            id="disabledTextInput"
                            class="form-control"
                            placeholder="enter name product"
                          />
                          <span
                            v-if="!$v.dataEdit.size.required && $v.size.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="CHANGE_ACTICE_MODAL_UPDATE">Close</button>
                  <button type="submit" class="btn btn-primary">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import { required, numeric } from "vuelidate/lib/validators";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
  props: ['dataEdit'],
  data() {
    return {
      avatar: null,
    };
  },
  validations: {
    dataEdit: {
      category_id: {required},
      name: {required},
      price: {required, numeric},
      color: {required},
      size: {required},
    }
  },
  methods: {
    ...mapActions(["getCategories", "editProduct", "isUpdateActiceModal"]),
    ...mapMutations(["CHANGE_ACTICE_MODAL_UPDATE"]),
    submitForm() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        let formData = new FormData();
        if(this.avatar !== null){
          formData.append("img", this.avatar);
        }
        formData.append("id", this.dataEdit.id);
        formData.append("category_id", this.dataEdit.category_id);
        formData.append("name", this.dataEdit.name);
        formData.append("price", this.dataEdit.price);
        formData.append("color", this.dataEdit.color);
        formData.append("size", this.dataEdit.size);
        const dataSeen =  {
          formData: formData,
          index:  this.dataEdit.index
        }
        this.editProduct(dataSeen);
      }
    },
    onChangeImg(e) {
      this.avatar = e.target.files[0];
    }
  },
  computed: {
    ...mapGetters(["islistCategories", "isActiveModalUpdate"]),
  },
  created() {
    this.getCategories();
  }
};
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