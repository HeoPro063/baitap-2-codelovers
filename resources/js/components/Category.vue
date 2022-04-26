<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header justify-content-between">
            <div class="container">
              <div class="row">
                <div class="col-md-6">
                  <h3 @click="resetDataCategory" class="card-title cursor-button">Categories</h3>
                </div>
                <div class="col-md-2">
                  <div class="input-group">
                    <div class="form-outline">
                      <input
                        type="text"
                        id="form1"
                        class="form-control"
                        v-model="search"
                        placeholder="Enter search..."
                      />
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="searchCategory"
                  >Tìm kiếm</button>
                </div>
                <div class="col-md-2">
                  <button
                    type="button"
                    class="btn btn-primary"
                    @click="CHANGE_ACTICE_MODAL_ADD_CATEGORY"
                  >New Category</button>
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
                  <th style="width: 70%">Name Category</th>
                  <th style="width: 25%">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(item,  index) in isListCategories" :key="item.id">
                  <td>{{stt(index)}}</td>
                  <td>{{item.name}}</td>
                  <td>
                    <button
                      @click="editCategory(item, index)"
                      type="button"
                      class="btn btn-secondary"
                    >Edit</button>
                    <button
                      @click="deleteCategory(item.id, index)"
                      class="btn btn-danger"
                      data-toggle="modal"
                      data-target="#exampleModal3"
                    >Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
         <div class="card-footer clearfix" v-if="isPaginate.total_page > 1">
            <ul class="pagination pagination-sm m-0 float-right">
              <li class="page-item" v-bind:class="[{disabled : isPaginate.page === 1}]">
                <a
                  class="page-link cursor-button"
                  @click="fetchCustomer(1)"
                >&laquo;</a>
              </li>
              <li class="page-item">
                <a class="page-link">Số trang {{this.isPaginate.page}} - {{this.isPaginate.total_page}}</a>
              </li>
              <li class="page-item"  v-bind:class="[{disabled : isPaginate.page === this.isPaginate.total_page}]">
                <a
                  class="page-link cursor-button"
                  @click="fetchCustomer(2)"
                >&raquo;</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <!-- add category -->
    <ModalAdd />
    <!-- edit category -->
    <ModalEdit :dataEdit="dataEdit"></ModalEdit>
    <!-- delete category -->
    <ModelDelete :dataDelete="dataDelete" :dataGet="dataGet"></ModelDelete>
  </div>
</template>

<script>
import { mapGetters, mapActions, mapMutations} from "vuex";
import ModalAdd from "../layout/modal/category/Add";
import ModalEdit from "../layout/modal/category/Edit";
import ModelDelete from "../layout/modal/category/Delete";

export default {
  created() {
    this.getPaginateCategory(this.dataGet);
  },
  data() {
    return {
      dataEdit: {
        index: "",
        id: "",
        name: ""
      },
      dataDelete: {
        id: "",
        index: ""
      },
      dataGet: {
        url_get: "api/category/paginate",
        dataSearch: ""
      },
      search: ""
    };
  },
  computed: {
    ...mapGetters(["isListCategories", "isPaginate"])
  },
  methods: {
    ...mapActions(["showCategory", "getPaginateCategory"]),
    ...mapMutations(["CHANGE_ACTICE_MODAL_ADD_CATEGORY", "CHANGE_ACTICE_MODAL_EDIT_CATEGORY"]),
    stt(number) {
          return number + 1;
      },
    editCategory(data, index) {
      this.dataEdit.index = index;
      this.dataEdit.id = data.id;
      this.dataEdit.name = data.name;
      this.CHANGE_ACTICE_MODAL_EDIT_CATEGORY()
    },
    deleteCategory(categoryId, index) {
      this.dataDelete.id = categoryId;
      this.dataDelete.index = index;
    },
    fetchCustomer(page) {
      let page_link = page === 1 ?  this.isPaginate.page - 1 :  this.isPaginate.page + 1;
      this.dataGet.url_get = `api/category/paginate?page=${page_link}`;
      this.getPaginateCategory(this.dataGet);
    },
    searchCategory(){
      this.dataGet.dataSearch = this.search;
      this.dataGet.url_get = `api/category/paginate`
      this.getPaginateCategory(this.dataGet);
    },
    resetDataCategory(){
      this.dataGet.dataSearch = '';
      this.dataGet.url_get = `api/category/paginate`
      this.getPaginateCategory(this.dataGet);
    }
  },
  
  // beforeUpdate(){
  //   this.getPaginateCategory(this.dataGet);
  // },
  components: {
    ModalAdd,
    ModalEdit,
    ModelDelete
  }
};
</script>

<style scoped>
  .cursor-button{
    cursor: pointer;
  }
</style>
