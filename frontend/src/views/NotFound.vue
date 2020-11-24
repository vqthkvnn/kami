<template>
<default-layout>
  <h3>Sorry</h3>
  <br/>
  <h1>404</h1>
</default-layout>
</template>

<script>
import DefaultLayout from "@/components/DefaultLayout";
export default {
  name: "NotFound",
  components: {DefaultLayout},
  watch: {
    title: {
      immediate: true,
      handler() {
        document.title = "Not Found Page - 404";
      },
    },
  },
}
</script>

<style scoped>

</style>