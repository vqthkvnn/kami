<template>
<div class="profile-preferences">
  <v-overlay :value="overlay">
    <v-progress-circular
        indeterminate
        size="64"
    ></v-progress-circular>
  </v-overlay>
  <h1>Thông tin tài khoản</h1>
  <v-divider></v-divider>
  <v-row>
    <h3 class="information-login">Thông tin đăng nhập</h3>
  </v-row>
  <br/>
  <v-row>
    <v-col cols="6">
      <p>Tên tài khoản: hihaha</p>
      <p>Email đăng ký: hihaha@gmail.com</p>
    </v-col>
    <v-col cols="6">

    </v-col>
  </v-row>
</div>
</template>

<script>
export default {
name: "ProfilePreferences",
  data(){
  return{
    overlay:true
  }
  },
  created() {
    setTimeout(() => {
      this.overlay = false
    }, 500);
  }
}
</script>

<style scoped>
.information-login{
  float: left;margin-left: 12px
}
</style>