<template>
  <div>
        <ul class="list-group mt-2">
            <li class="list-group-item active">Theo danh sách màu</li>
            <select class="custom-select" @change="chooseSize" v-model="searchSize">
                <option value="" selected>Tìm size</option>
                <option
                          v-for="item in isListSize"
                          :key="item.id"
                          :value="item.id"
                        >{{item.name}}</option>
            </select>
        </ul>
  </div>
</template>

<script>

import { mapActions , mapGetters } from "vuex";

export default {
  props: ['dataGet'],
  data() {
    return {
      searchSize: ''
    }
  },
  computed: {
    ...mapGetters(['isListSize'])
  },
  methods: {
    ...mapActions(['getPaginateProduct', 'getSize']),
    chooseSize() {
      this.dataGet.action = 5;
      this.dataGet.dataSearch = this.searchSize
      this.getPaginateProduct(this.dataGet)
    }
  },
  created() {
    this.getSize()
  }
}
</script>

