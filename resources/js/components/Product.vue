<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card-header justify-content-between">
          <div class="container">
            <div class="row">
              <div class="col-md-8">
                <h3 class="card-title">Categories</h3>
              </div>
              <div class="col-md-2">
                <div class="input-group">
                  <div class="form-outline">
                    <input
                      type="search"
                      id="form1"
                      class="form-control"
                      placeholder="Enter search..."
                      v-model="search"
                    />
                  </div>
                </div>
              </div>
              <div class="col-md-2">
                <button
                  type="button"
                  class="btn btn-primary"
                  @click="CHANGE_ACTICE_MODAL_ADD"
                >New Product</button>
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
                <th>Category</th>
                <th>Name Product</th>
                <th>Avatar</th>
                <th>Price</th>
                <th>Color</th>
                <th>Size</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item , index) in isListProduct" :key="item.id">
                <td>{{index}}</td>
                <th>{{item.nameCategory}}</th>
                <td>{{item.name}}</td>
                <td>
                  <img
                    style="width:150px;height:150px"
                    :src="getImgUrl(item.avatar)"
                    alt="..."
                    class="rounded img-thumbnail"
                  />
                </td>
                <td>{{item.price}}</td>
                <td>{{item.color}}</td>
                <td>{{item.size}}</td>
                <td>
                  <button
                    type="button"
                    class="btn btn-secondary"
                    data-toggle="modal"
                    data-target="#exampleModal2"
                    @click="clickEditProduct({
                      index: index, 
                      id: item.id, 
                      category_id: item.category_id,
                      name: item.name,
                      avatar: item.avatar,
                      price: item.price,
                      color: item.color,
                      size: item.size,
                    })"
                  >Edit</button>
                  <button
                    class="btn btn-danger"
                    data-toggle="modal"
                    data-target="#exampleModal3"
                    @click="clickDeleteProduct(item.id, index)"
                  >Delete</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix">
          <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item" v-bind:class="[{disabled : !isProductPaginate.prev_page_url}]">
              <a class="page-link cursor-pointer" @click="fetchCustomer(isProductPaginate.prev_page_url)" >&laquo;</a>
            </li>
            <div  style="display: flex" v-for="item in isProductPaginate.links" :key="item.id">
              <li v-bind:class="[{active : item.active}]" v-if="item.label !== '&laquo; Previous' && item.label !== 'Next &raquo;'" class="page-item">
                <a class="page-link cursor-pointer" @click="fetchCustomer(item.url)">{{item.label}}</a>
              </li>
            </div>
            <li class="page-item" v-bind:class="[{disabled : !isProductPaginate.next_page_url}]">
              <a class="page-link cursor-pointer" @click="fetchCustomer(isProductPaginate.next_page_url)" >&raquo;</a>
            </li>
          </ul>
        </div>

       
        <!-- add modal -->
        <AddModal />
        <DeleteModal :dataDelete="dataDelete" />
        <EditModal :dataEdit="dataEdit" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";

import AddModal from "../layout/modal/product/Add.vue";
import DeleteModal from "../layout/modal/product/DeleteModal.vue";
import EditModal from "../layout/modal/product/Edit.vue";

export default {
  watch: {
    search() {
      this.dataGet.dataSeach = this.search;
      this.dataGet.url_get = "api/category/product/paginate";
      this.getPaginateProduct(this.dataGet);
    }
  },
  data() {
    return {
      dataDelete: {
        idProduct: null,
        index: null
      },
      dataEdit: {
        index: null,
        id: null,
        category_id: null,
        name: null,
        avatar: null,
        price: null,
        color: null,
        size: null
      },
      dataGet: {
        url_get: "api/category/product/paginate",
        dataSeach: ""
      },
      search: ""
    };
  },
  computed: {
    ...mapGetters(["isListProduct", "isProductPaginate"]),
    
  },
  methods: {
    ...mapActions(["getProducts", "getPaginateProduct"]),
    ...mapMutations(["CHANGE_ACTICE_MODAL_ADD", "CHANGE_ACTICE_MODAL_UPDATE"]),
    getImgUrl(pet) {
      return "images/" + pet;
    },
    clickDeleteProduct(idProduct, index) {
      this.dataDelete.idProduct = idProduct;
      this.dataDelete.index = index;
    },
    clickEditProduct(obj) {
      this.dataEdit.index = obj.index;
      this.dataEdit.id = obj.id;
      this.dataEdit.category_id = obj.category_id;
      this.dataEdit.name = obj.name;
      this.dataEdit.avatar = obj.avatar;
      this.dataEdit.price = obj.price;
      this.dataEdit.color = obj.color;
      this.dataEdit.size = obj.size;
      this.CHANGE_ACTICE_MODAL_UPDATE();
    },
    fetchCustomer(page_url) {
      this.dataGet.url_get = page_url;
      this.getPaginateProduct(this.dataGet);
    }
  },
  created() {
    // this.getProducts();
    this.getPaginateProduct(this.dataGet);

  },
  components: {
    AddModal,
    DeleteModal,
    EditModal
  }
};
</script>

<style scoped>
  .cursor-pointer{
    cursor: pointer;
  }
</style>