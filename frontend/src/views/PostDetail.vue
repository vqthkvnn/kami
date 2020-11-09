<template>
<default-layout>
  <post/>
  <comment/>
</default-layout>
</template>

<script>
import DefaultLayout from "@/components/DefaultLayout";
import Post from "@/components/Post";
import Comment from "@/components/Comment";
export default {
name: "PostDetail",
  components: {Comment, Post, DefaultLayout}
}
</script>

<style scoped>

</style>