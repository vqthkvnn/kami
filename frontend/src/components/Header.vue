<template>
<div>
    <div class="header">
    <v-row no-gutters>
      <v-col cols="3" style="color:white;">
         KAMI
        <v-card
          class="pa-2"          
          tile
          style="width:fit-content;"
        >
         COMUNITY
        </v-card>
      </v-col>
      <v-col cols="5">
      </v-col>
      <v-col cols="2" style="margin: auto;">
        <v-btn
        depressed
        small
        style="background-color:crimson;color:white;font-family:inherit;"
        >
          <ModelLogin></ModelLogin>
        </v-btn>
        <v-btn
        depressed
        small
        style="background-color:crimson;color:white;margin-left:5px;font-family:inherit;"
        >
          <v-icon>{{icons.mdiAccount}}</v-icon> login
        </v-btn>
      </v-col>
      <v-col cols="2"  style="margin: auto;">
          <div style="">
            <v-icon class="1" style="padding-right: 20px;color: rgba(243, 233, 233, 0.54);">fas fa-search</v-icon>
            <v-icon class="2"  style="color:rgb(243 233 233 / 54%);">fas fa-list</v-icon>
          </div>
      </v-col>
    </v-row>
  </div>
  <div>
    <v-row no-gutters
    style="height:90px;">
      <div style="margin:auto;">
        QUẢNG CÁO
      </div>
    </v-row>
  </div>
</div>
</template>
<script>
 import ModelLogin from './Login'
 import {
    mdiAccount,
    mdiPencil,
    mdiShareVariant,
    mdiDelete,
  } from '@mdi/js'
export default {
    components:{
      ModelLogin,
    },
    data: () => ({
      icons: {
        mdiAccount,
        mdiPencil,
        mdiShareVariant,
        mdiDelete,
      },
    }),
  }
</script>

<style>
.header{
 line-height: 30px;
 background-color: #333;
}
</style>
