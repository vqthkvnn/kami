<template>
  <div>
    <app-header></app-header>
    <app-body></app-body>
  </div>
</template>
<script>
import Modalcomment from './components/comment.vue'
import Modalpost from './components/Post.vue'
export default {
  components:{
    Modalcomment,
    Modalpost,
  }
  
}
</script>