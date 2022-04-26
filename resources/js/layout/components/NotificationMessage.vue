<template>
    <div :class="Notification" class="alert alert-dismissible fade show  notification" role="alert">
        {{item.text}}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</template>

<script>
import { mapMutations } from 'vuex'
export default {
    props: ['item'],
    data() {
      return {
          timeOut: ''
      }
    },
    computed: {

        Notification() {
            return `alert-${this.item.type}`
        }
    },
    methods: {
        ...mapMutations(['REMOVE_ALERT'])
    },
    created() {
      this.timeOut = setTimeout(() => {
        this.REMOVE_ALERT(this.item)
      }, 3000)
    },
    beforeDestroy() {
        clearTimeout(this.timeOut)
    }
}
</script>

<style>
    
</style>