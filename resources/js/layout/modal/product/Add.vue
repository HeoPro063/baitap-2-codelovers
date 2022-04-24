<template>
  <div v-if="isActiveModalAdd">
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wapper">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="close" @click="CHANGE_ACTICE_MODAL_ADD">
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
                            <select v-model="category_id" class="custom-select">
                              <option
                                v-for="item  in islistCategories"
                                :key="item.id"
                                :value="item.id"
                              >{{item.name}}</option>
                            </select>
                          </div>
                          <span
                            v-if="!$v.category_id.required && $v.category_id.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Name Product</label>
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
                          <span
                            v-if="!$v.avatar.required && $v.avatar.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="disabledTextInput">Price</label>
                          <input
                            v-model="price"
                            type="number"
                            id="disabledTextInput"
                            class="form-control"
                            placeholder="enter name product"
                          />
                          <span
                            v-if="!$v.price.required && $v.price.$dirty"
                            class="text-danger"
                          >Required field!</span>
                          <span
                            v-if="!$v.price.numeric && $v.price.$dirty"
                            class="text-danger"
                          >Numeric field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Color</label>
                          <div>
                            <select v-model="color" class="custom-select">
                               <option
                                v-for="item  in isListColor"
                                :key="item.id"
                                :value="item.id"
                              >{{item.name}}</option>
                            </select>
                          </div>
                          <span
                            v-if="!$v.color.required && $v.color.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                        <div class="form-group">
                          <label for="disabledTextInput">Size</label>
                          <div>
                            <select v-model="size" class="custom-select">
                              <option
                                v-for="item in isListSize"
                                :key="item.id"
                                :value="item.id"
                              >{{item.name}}</option>
                            </select>
                          </div>
                          <span
                            v-if="!$v.size.required && $v.size.$dirty"
                            class="text-danger"
                          >Required field!</span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" @click="CHANGE_ACTICE_MODAL_ADD">Close</button>
                  <button type="submit" class="btn btn-primary" :disabled="this.isStatus">
                    <div v-if="this.isStatus">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Đang tải...
                    </div>
                    <div v-else>
                      Thêm sản phẩm
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
</template>

<script>
import { required, numeric } from "vuelidate/lib/validators";
import { mapActions, mapGetters, mapMutations } from "vuex";

export default {
  data() {
    return {
      category_id: "",
      name: "",
      avatar: "",
      price: "",
      color: "",
      size: "",
      status: false
    };
  },
  validations: {
    category_id: { required },
    name: { required },
    avatar: { required },
    price: { required, numeric },
    color: { required },
    size: { required }
  },
  methods: {
    ...mapActions(["getCategories", "addProduct", "isAddActiceModal"]),
    ...mapMutations(["CHANGE_ACTICE_MODAL_ADD", "CHANGE_STATUS"]),
    submitForm() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.CHANGE_STATUS();
        let formData = new FormData();
        formData.append("img", this.avatar);
        formData.append("category_id", this.category_id);
        formData.append("name", this.name);
        formData.append("price", this.price);
        formData.append("color_id", this.color);
        formData.append("size_id", this.size);
        this.addProduct(formData);
        this.category_id = ''
        this.name = ''
        this.avatar = ''
        this.price = ''
        this.color = ''
        this.size = ''
        this.$nextTick(() => { this.$v.$reset() })
      }
    },
    onChangeImg(e) {
      this.avatar = e.target.files[0];
    }
  },
  computed: {
    ...mapGetters(["islistCategories", "isActiveModalAdd", "isStatus", "isListColor", "isListSize"])
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