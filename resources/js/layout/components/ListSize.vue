<template>
  <div>
        <ul class="list-group mt-2">
            <li class="list-group-item active">Theo size</li>
            <li @click="chooseColor('l')" class="list-group-item cursor-pointer item-product">L</li>
            <li @click="chooseColor('m')" class="list-group-item cursor-pointer item-product">M</li>
            <li @click="chooseColor('xl')" class="list-group-item cursor-pointer item-product">XL</li>
            <li @click="chooseColor('xxl')" class="list-group-item cursor-pointer item-product">XXL</li>
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

