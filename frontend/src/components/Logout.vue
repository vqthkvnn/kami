<template>

</template>

<script>
export default {
name: "Logout",
  created() {
    this.$cookie.delete('token');
    this.$router.push('/');
  }
}
</script>

<style scoped>

</style>