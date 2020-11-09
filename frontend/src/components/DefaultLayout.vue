<template>
  <v-app>
      <div class="header">
        <v-row no-gutters>
          <v-col cols="3" style="color: white">
            KAMI
            <v-card class="pa-2" tile style="width: fit-content">
              COMUNITY
            </v-card>
          </v-col>
          <v-col cols="5"> </v-col>
          <v-col cols="2" justify="center" style="margin: auto" data-app>
            <v-dialog v-model="dialogreg" persistent>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                    depressed
                    small
                    style="
                  background-color: crimson;
                  color: white;
                  font-family: inherit;
                "
                    v-bind="attrs"
                    v-on="on"
                >
                  sign up
                </v-btn>
              </template>
              <v-card style="
                width: 75%;
                margin: auto;
            ">
                <v-card-title class="headline" style="font-size: 40px;font-family: cursive;justify-content: center;">
                  SIGN UP
                </v-card-title>
                <Register/>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" text @click="dialogreg = false">
                    CLOSE
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
            <v-dialog v-model="dialoglog" persistent>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                    depressed
                    small
                    style="
              background-color: crimson;
              color: white;
              margin-left: 5px;
              font-family: inherit;
            "
                    v-bind="attrs"
                    v-on="on"
                >
                  <v-icon>{{ icons.mdiAccount }}</v-icon> login
                </v-btn>
              </template>
              <v-card style="
                width: 75%;
                margin: auto;
            ">
                <v-card-title class="headline" style="font-size: 40px;font-family: cursive;justify-content: center;">
                  SIGN UP
                </v-card-title>
                <Login/>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="green darken-1" text @click="dialoglog = false">
                    CLOSE
                  </v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-col>
          <v-col cols="2" style="margin: auto">
            <div style="">
              <v-icon
                  class="1"
                  style="padding-right: 20px; color: rgba(243, 233, 233, 0.54)"
              >fas fa-search</v-icon
              >
              <v-icon class="2" style="color: rgb(243 233 233 / 54%)"
              >fas fa-list</v-icon
              >
            </div>
          </v-col>
        </v-row>
      </div>
      <div>
        <v-row no-gutters style="height: 90px">
          <div style="margin: auto">QUẢNG CÁO</div>
        </v-row>
      </div>
    <v-main>
      <v-container>
        <slot/>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import Body from "@/components/Body";
import { mdiAccount, mdiPencil, mdiShareVariant, mdiDelete } from "@mdi/js";
import Login from "@/components/Login";
import Register from "@/components/Register";
export default {
name: "DefaultLayout",
  components: {Body, Login, Register},
  data() {
    return {
      icons: {
        mdiAccount,
        mdiPencil,
        mdiShareVariant,
        mdiDelete,
      },
      dialoglog: false,
      dialogreg:false,
    };
  },
}
</script>

<style scoped>
.header {
  line-height: 30px;
  background-color: #333;
}
</style>