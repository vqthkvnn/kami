<template>
    <v-container>
        <v-row style="
    border-top-style: groove;
"> 
          <v-col cols="1">
            <v-img
              height="45"
              width="45"
              lazy-src="https://picsum.photos/id/11/10/6"
              src="https://picsum.photos/id/11/500/300"
              style="border-radius:100%">
            </v-img>
          </v-col>
          <v-col cols="3" style="position: relative;right: 30px;color: rgb(186, 84, 1);font-weight: bold;font-size: 18px;">
            lurtnowski
          </v-col>
           <v-col cols="6">
          </v-col>
          <v-col cols="1" style="font-size:15px;color:#999;">
              1h
          </v-col>
          <v-col cols="1" style="font-size:15px;color:#999;">
              #1
          </v-col>                        
        </v-row>
      <v-row>
        <v-col cols="1">
        </v-col>
        <v-col cols="11">
            <p>You run this in a terminal in your project root. It will create a node_modules folder with the library (and any dependencies it might have) inside. Note, that to run that command, you must have Node and npm installed on your machine.

Alternatively, you can grab the library off of unpkg:</p>
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="10">

        </v-col>
        <v-col cols="1">
          <a href="">
            <i class="far fa-heart"></i>
          </a>          
        </v-col>
        <v-col cols="1">
          <a href="">
            <i class="fas fa-share-alt-square" style="width:20px;"></i>
          </a>          
        </v-col>
      </v-row>
    </v-container>
</template>
<script>
export default {
    data () {
      return {
       
      }
    },
}
</script>