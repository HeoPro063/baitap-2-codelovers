<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 mt-5">
          <ListColor :dataGet="dataGet" />
          <ListSize :dataGet="dataGet" />
      </div>
      <div class="col-md-10">
        <div class="card-header justify-content-between">
          <div class="container">
            <div class="row">
              <div class="col-md-2">
                <h3 class="card-title cursor-pointer" @click="resetData">Categories</h3>
              </div>
              <div class="col-md-2">
                 <select class="custom-select" @change="searchProductCate" v-model="searchProductCategory">
                  <option value="" selected>Theo danh mục</option>
                  <option
                            v-for="item  in islistCategories"
                            :key="item.id"
                            :value="item.id"
                          >{{item.name}}</option>
                </select>
              </div>
              <div class="col-md-2">
              <SearchPrice :dataGet="dataGet"/>
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
                  @click="submitSearch"
                >Tìm kiếm</button>
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
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(item , index) in isListProduct" :key="item.id" class="item-product cursor-pointer">
                  <td>{{stt(index)}}</td>
                  <th>{{item.category_name}}</th>
                  <td
                    @click="showDetailProduct(item.id)"
                    class="text text-primary"
                    data-toggle="modal"
                    data-target="#exampleModal"
                     data-backdrop="static" data-keyboard="false"
                    >
                      {{jsUcfirst(item.name)}}
                  </td>
                  <td>
                    <img
                      style="width:150px;height:150px"
                      :src="getImgUrl(item.avatar)"
                      alt="..."
                      class="rounded img-thumbnail"
                    />
                  </td>
                  <td>
                    <button
                      type="button"
                      class="btn btn-secondary"
                      data-toggle="modal"
                      data-target="#exampleModal2"
                      @click="clickEditProduct(item, index)"
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
        <div class="card-footer clearfix" v-if="isProductPaginate.total_page > 1">
          <ul class="pagination pagination-sm m-0 float-right">
            <li class="page-item"   v-bind:class="[{disabled : isProductPaginate.page === 1}]">
              <a class="page-link cursor-pointer" @click="fetchCustomer('prev')">&laquo;</a>
            </li>
            <div style="display: flex" v-for="item in isProductPaginate.total_page" :key="item.id">
              <li class="page-item" v-bind:class="[{active : isProductPaginate.page === item}]" @click="fetchCustomer(item)">
                <a class="page-link cursor-pointer">{{item}}</a>
              </li>
            </div>
            <li class="page-item" v-bind:class="[{disabled : isProductPaginate.page === isProductPaginate.total_page}]" >
              <a class="page-link cursor-pointer" @click="fetchCustomer('next')">&raquo;</a>
            </li>
          </ul>
        </div>
        <!-- add modal -->
        <AddModal />
        <DeleteModal :dataDelete="dataDelete" />
        <EditModal :dataEdit="dataEdit" />
        <Detail :dataGet="dataGet" />
      </div>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";

import AddModal from "../layout/modal/product/Add.vue";
import DeleteModal from "../layout/modal/product/DeleteModal.vue";
import EditModal from "../layout/modal/product/Edit.vue";
import Detail from "../layout/modal/product/Detail.vue";
import ListColor from "../layout/components/ListColor.vue";
import ListSize from "../layout/components/ListSize.vue";
import SearchPrice from "../layout/components/SearchPrice.vue";

export default {
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
        color_id: null,
        size_id: null
      },
      dataGet: {
        url_get: "api/category/product/paginate",
        dataSearch: "",
        action: 1
      },
      search: "",
      searchProductCategory: "" 
    };
  },
  computed: {
    ...mapGetters(["isListProduct", "isProductPaginate", "islistCategories"]),
    
  },
  methods: {
    ...mapActions(["getProducts", "getPaginateProduct", "getDetailProduct", "getDetailProduct"]),
    ...mapMutations(["CHANGE_ACTICE_MODAL_ADD", "CHANGE_ACTICE_MODAL_UPDATE"]),
    getImgUrl(pet) {
      return "images/" + pet;
    },
     stt(number) {
          return number + 1;
      },
    clickDeleteProduct(idProduct, index) {
      this.dataDelete.idProduct = idProduct;
      this.dataDelete.index = index;
    },
    clickEditProduct(item, index) {
      this.dataEdit.index = index;
      this.dataEdit.id = item.id;
      this.dataEdit.category_id = item.category_id;
      this.dataEdit.name = item.name;
      this.dataEdit.avatar = item.avatar;
      this.dataEdit.price = item.price;
      this.dataEdit.color_id = item.color_id;
      this.dataEdit.size_id = item.size_id;
      this.CHANGE_ACTICE_MODAL_UPDATE();
    },
    fetchCustomer(page) {
      let page_link;

      if(page === 'prev'){
        page_link = this.isProductPaginate.page - 1;
      }else if(page === 'next'){
        page_link = this.isProductPaginate.page + 1;
      }else{
        page_link = page;
      }
      this.dataGet.url_get = `api/category/product/paginate?page=${page_link}`;
      this.getPaginateProduct(this.dataGet);
    },
    submitSearch(){
      this.dataGet.dataSearch = this.search;
      this.dataGet.url_get = "api/category/product/paginate"
      this.dataGet.action = 1;
      this.getPaginateProduct(this.dataGet);
    },
    resetData() {
      this.dataGet.dataSearch = "";
      this.search = "";
      this.dataGet.url_get = "api/category/product/paginate";
      this.action = 1;
      this.getPaginateProduct(this.dataGet);
    },
    detailProduct(id){
      this.getDetailProduct(id)
    },
    showDetailProduct(id){
      this.getDetailProduct(id)
    },
    jsUcfirst(string) 
    {
        return string.charAt(0).toUpperCase() + string.slice(1);
    },
    formatNumber(price) {
        var formatter = price ? price.toLocaleString('vi', {style : 'currency', currency : 'VND'}) : '';
        return formatter
    },
    searchProductCate() {
      if(this.searchProductCategory != '') {
        this.dataGet.url_get = "api/category/product/paginate",
        this.dataGet.dataSearch = this.searchProductCategory,
        this.dataGet.action = 4
        this.getPaginateProduct(this.dataGet);
      }else{
        this.dataGet.url_get = "api/category/product/paginate",
        this.dataGet.dataSearch = "",
        this.dataGet.action = 1
        this.getPaginateProduct(this.dataGet);
      }
    }
  },
  created() {
    this.getPaginateProduct(this.dataGet);
  },
  components: {
    AddModal,
    DeleteModal,
    EditModal,
    ListColor,
    ListSize,
    Detail,
    SearchPrice
  }
};
</script>

<style scoped>
  
</style>