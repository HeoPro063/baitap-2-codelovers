<template>
  <div>
        <ul class="list-group mt-5">
            <li class="list-group-item active">Theo danh sách màu</li>
            <select class="custom-select" @change="chooseColor" v-model="searchColor">
                <option value="" selected>Tìm màu</option>
                <option
                          v-for="item  in isListColor"
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
      searchColor: ''
    }
  },
  computed: {
    ...mapGetters(['isListColor'])
  },
  methods: {
    ...mapActions(['getPaginateProduct', 'getColor']),
    chooseColor() {
      this.dataGet.action = 2;
      this.dataGet.dataSearch = this.searchColor
      this.getPaginateProduct(this.dataGet)
    }
  },
  created() {
    this.getColor()
  }
}
</script>

