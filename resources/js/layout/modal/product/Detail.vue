<template>
  <!-- Modal -->
  <div
    class="modal fade"
    id="exampleModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="false"
  >
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Chi tiết sản phẩm</h5>
          <button @click="closeModalDetail" type="button" class="btn btn-danger" data-dismiss="modal" aria-label="Close">
          Close
          </button>
        </div>
        <div class="modal-body">
          <div class="container"> 
            <div class="row">
              <div class="col-12-md">
                  <h5>Tên sản phẩm: <strong>{{jsUcfirst(this.isDataProductDetail.name)}}</strong> </h5>
                  <p>Loại sản phẩm: <strong>{{jsUcfirst(this.isDataProductDetail.category_name)}}</strong></p>
                  <p>Giá: {{formatNumber(this.isDataProductDetail.price)}}</p>
                  <p>Màu:  {{jsUcfirst(this.isDataProductDetail.color)}}</p>
                  <p>Size: {{formatStringSize(this.isDataProductDetail.size)}}</p>
              </div>
            </div>
            <div class="row">
              <div class="col">
                <h5 class="title">Xem thêm sản phẩm liên quan</h5>
                <hr>
              </div>
            </div>
            <div class="row">
              <div v-for="item in isDataProductRalate" :key="item.id" class="col-3">
                <div class="card" style="width: 16rem;">
                  <img class="card-img-top style-image" :src="getImgUrl(item.avatar)" alt="Card image cap">
                  <div class="card-body">
                    <h5 class="card-title">{{formatString(item.name)}}</h5>
                    <p class="card-text">Color: {{jsUcfirst(item.color)}}</p>
                    <p class="card-text">Price: {{formatNumber(item.price)}}</p>
                    <p class="card-text">Size: {{formatStringSize(item.size)}}</p>
                    <button @click="redirect(item.id)" class="btn btn-primary" type="button" data-dismiss="modal" aria-label="Close">View more</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import { mapGetters, mapMutations, mapActions } from "vuex";
export default {
  props: ['dataGet'],
  data() {
    return {
      detail: '',
    }
  },
  computed: {
    ...mapGetters(['isDataProductDetail', 'isDataProductRalate'])
  },
  methods: {
    ...mapMutations(['RESET_DATA_PRODUCT_DETAIL']),
    ...mapActions(['getPaginateProduct']),
    closeModalDetail() {
      this.RESET_DATA_PRODUCT_DETAIL()
    },
    jsUcfirst(string) 
    {
      const text = string ?  string.charAt(0).toUpperCase() + string.slice(1) : '';
      return text ;
    },
    formatNumber(price) {
        var formatter = price ? price.toLocaleString('vi', {style : 'currency', currency : 'VND'}) : '';
        return formatter
    },
    formatStringSize(string) {
      var text = string ? string.toUpperCase() : ''
      return text
    },
    getImgUrl(pet) {
      return "images/" + pet;
    },
    formatString(str){
      var str_Uc = str ? this.jsUcfirst(str) : ''
      var text = str_Uc.length < 15 ? str_Uc : str_Uc.slice(0, 15) + '...'
      return text
    },
    redirect(id) {
      this.dataGet.dataSearch = id
      this.dataGet.action = 3
      this.dataGet.url_get = "api/category/product/paginate"
      this.getPaginateProduct(this.dataGet)
    },
  }
};
</script>

<style scoped>
    .modal-ku {
        width: 1000px;
        margin: auto;
    }
    .style-image{
      width:100%;
      height:200px;
      object-fit: cover;
      margin: 0 auto;
    }
     .cursor-pointer {
        cursor: pointer;
    }
</style>