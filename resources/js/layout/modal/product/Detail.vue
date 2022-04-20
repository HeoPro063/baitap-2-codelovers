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
          <button @click="closeModalDetail" type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="container"> 
            <div class="row">
              <div class="col-12-md">
                  <h5>Tên sản phẩm: <strong>{{jsUcfirst(this.isDataProductDetail.name)}}</strong> </h5>
                  <p>Loại sản phẩm: <strong>{{jsUcfirst(this.isDataProductDetail.category_name)}}</strong></p>
                  <p>Giá: {{formatNumber(this.isDataProductDetail.price)}}</p>
                  <p>Màu: <span :class="formatStringColor(this.isDataProductDetail.color)"> {{jsUcfirst(this.isDataProductDetail.color)}}</span></p>
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
              <div v-for="item in this.isDataProductRalate" :key="item.id" class="col m-1">
                <div class="card">
                  <img :src="getImgUrl(item.avatar)" class="card-img-top rounded img-thumbnail style-image">
                  <div class="card-body">
                    <h5 class="card-title">{{formatString(item.name)}}</h5>
                    <p class="card-text" >Màu: <span :class="formatStringColor(item.color)">{{item.color}}</span> </p>
                    <p class="card-text">Size: {{item.size}}</p>
                    <a @click="redirect(item.id)" class="btn btn-primary cursor-pointer" data-dismiss="modal" aria-label="Close">Xem...</a>
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
      detail: ''
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
    formatStringColor(string){
      var color = '';
      if(string === 'red'){
        color = 'text-danger'
      }else if(string === 'green') {
        color = 'text-success' 
      }else if(string === 'blue'){
        color = 'text-primary'
      }else if(string === 'black'){
        color = 'text-dark'
      }
      return color
    },
    formatStringSize(string) {
      var text = string ? string.toUpperCase() : ''
      return text
    },
    getImgUrl(pet) {
      return "images/" + pet;
    },
    formatString(str){
      var text = str ? str.length < 15 ? str : str.slice(0, 15) + '...' : ''
      return text
    },
    redirect(id) {
      this.dataGet.dataSeach = id
      this.dataGet.action = 3
      this.dataGet.url_get = "api/category/product/paginate"
      this.getPaginateProduct(this.dataGet)
    }
  }
};
</script>

<style scoped>
    .modal-ku {
        width: 1000px;
        margin: auto;
    }
    .style-image{
      width:150px;
      height:150px;
      object-fit: cover;
      margin: 0 auto;
    }
     .cursor-pointer {
        cursor: pointer;
    }
</style>