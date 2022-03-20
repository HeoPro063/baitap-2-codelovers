<template>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="alert alert-danger mt-2" role="alert" v-if="isAlert">Error !</div>
        <div class="card">
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
                    data-toggle="modal"
                    data-target="#exampleModal"
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
                  <td>{{index}}</td>
                  <td>{{item.name}}</td>
                  <td>
                    <button
                      @click="editCategory(item, index)"
                      type="button"
                      class="btn btn-secondary"
                      data-toggle="modal"
                      data-target="#exampleModal2"
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
          <!-- /.card-body -->
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
              <li class="page-item" v-bind:class="[{disabled : !isPaginate.prev_page_url}]">
                <a
                  class="page-link"
                  @click="fetchCustomer(isPaginate.prev_page_url)"
                  href="#"
                >&laquo;</a>
              </li>
              <li class="page-item">
                <a class="page-link">Số trang {{isPaginate.current_page}}-{{isPaginate.last_page}}</a>
              </li>
              <li class="page-item" v-bind:class="[{disabled : !isPaginate.next_page_url}]">
                <a
                  class="page-link"
                  @click="fetchCustomer(isPaginate.next_page_url)"
                  href="#"
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
    <ModalEdit :dataedit="dataEdit"></ModalEdit>
    <!-- delete category -->
    <ModelDelete :dataDelete="dataDelete"></ModelDelete>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import ModalAdd from "../layout/modal/category/Add";
import ModalEdit from "../layout/modal/category/Edit";
import ModelDelete from "../layout/modal/category/Delete";

export default {
  watch: {
    search() {
      this.dataGet.dataSeach = this.search;
      this.dataGet.url_get = "api/category/paginate";
      this.getPaginateCategory(this.dataGet);
    }
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
        dataSeach: ""
      },
      search: ""
    };
  },
  computed: {
    ...mapGetters(["isListCategories", "isAlert", "isPaginate"])
  },
  methods: {
    ...mapActions(["showCategory", "getPaginateCategory"]),
    editCategory(data, index) {
      this.dataEdit.index = index;
      this.dataEdit.id = data.id;
      this.dataEdit.name = data.name;
    },
    deleteCategory(categoryId, index) {
      this.dataDelete.id = categoryId;
      this.dataDelete.index = index;
    },
    fetchCustomer(page_url) {
      this.dataGet.url_get = page_url;
      this.getPaginateCategory(this.dataGet);
    }
  },
  created() {
    this.getPaginateCategory(this.dataGet);
  },
  components: {
    ModalAdd,
    ModalEdit,
    ModelDelete
  }
};
</script>
