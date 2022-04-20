<template>
  <div>
        <ul class="list-group mt-5">
            <li class="list-group-item active">Theo danh sách màu</li>
            <li @click="chooseColor('red')" class="list-group-item cursor-pointer item-product">Đỏ</li>
            <li @click="chooseColor('green')" class="list-group-item cursor-pointer item-product">Xanh lá</li>
            <li @click="chooseColor('blue')" class="list-group-item cursor-pointer item-product">Xanh nước biển</li>
            <li @click="chooseColor('black')" class="list-group-item cursor-pointer item-product">Đen</li>
        </ul>
  </div>
</template>

<script>

import { mapActions } from "vuex";

export default {
  props: ['dataGet'],
  methods: {
    ...mapActions(['getPaginateProduct']),
    chooseColor(param) {
      this.dataGet.action = 2;
      this.dataGet.dataSeach = param
      this.getPaginateProduct(this.dataGet)
    }
  }
}
</script>

