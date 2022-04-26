<template>
  <div class="alert-list">
      <NotificationMessage v-for="item in isListAlert" :key="item.id" :item="item" />
  </div>
</template>

<script>

import { mapGetters } from "vuex";
import NotificationMessage from './NotificationMessage.vue'

export default {
    
    computed: {
      ...mapGetters(['isListAlert'])
    },
    components: {
      NotificationMessage
    }
}
</script>

<style>
  .alert-list{
    position: fixed;
    top: 100px;
    z-index: 100;
    right: 0;
    margin-right: 15px;
    width: 320px;
  }
</style>