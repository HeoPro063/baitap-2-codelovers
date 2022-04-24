<template>
  <div>
       <select class="custom-select" v-model="dataSearch" @change="searchProductPrice">
            <option selected :value="''">Tất cả</option>
            <option :value="'max'">Giá lớn nhất</option>
            <option :value="'min'">Giá nhỏ nhất</option>
        </select>
  </div>
</template>

<script>
import { mapActions } from 'vuex'
export default {
    props: ['dataGet'],
    data() {
        return {
            dataSearch: ''
        }
    },
    methods: {
        ...mapActions(['getPaginateProduct']),
        searchProductPrice() {
            this.dataGet.action = 6;
            this.dataGet.dataSearch = this.dataSearch
            this.getPaginateProduct(this.dataGet)
        }
    }
}
</script>

<style>

</style>