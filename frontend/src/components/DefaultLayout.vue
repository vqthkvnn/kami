<template>
  <v-app>
    <div class="header">
      <v-row no-gutters>
        <v-col cols="3" style="color: white">
          <a href="/">
            KAMI
            <v-card class="pa-2" tile style="width: fit-content">
              COMUNITY
            </v-card>
          </a>
        </v-col>
        <v-col cols="5"></v-col>
        <v-col cols="2" style="margin: auto">
          <div>
            <v-text-field
                color="white"
                label="Search"
                v-model="query"
                @keydown.enter="searchWithQuery"
                style="padding-right: 20px; color: white"
                prepend-icon="fas fa-search"
            ></v-text-field>
          </div>
        </v-col>
        <v-col cols="2" justify="center" style="margin: auto" data-app v-if="!isLogin">
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
                <v-icon>{{ icons.mdiAccount }}</v-icon>
                login
              </v-btn>
            </template>
            <v-card style="
                width: 75%;
                margin: auto;
            ">
              <v-card-title class="headline" style="font-size: 40px;font-family: cursive;justify-content: center;">
                LOGIN
              </v-card-title>
              <Login />
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="green darken-1" text @click="dialoglog = false">
                  CLOSE
                </v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-col>
        <v-col cols="2" style="margin: auto" v-else>
          <div>
            <v-menu rounded="lg" offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                    class="mx-2" fab dark small
                    color="pink"
                    v-bind="attrs"
                    v-on="on"
                    @click="loadNotification">
                  <v-icon dark>
                    mdi-bell-ring
                  </v-icon>
                </v-btn>
              </template>
              <v-list>
                <v-list-item
                    v-for="(item, index) in itemNotification"
                    :key="index">
                  <v-list-item-title>{{ item.notification_content }}</v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>
            <v-menu offset-y>
              <template v-slot:activator="{ on, attrs }">
                <v-btn icon x-large v-bind="attrs"
                       v-on="on">
                  <v-avatar>
                    <img
                        :src="avatar"
                        alt="Null">
                  </v-avatar>
                </v-btn>
              </template>
              <v-list>
                <v-list-item link :to="item.link" v-for="(item, index) in menuAvtItems" :key="index">
                  <v-list-item-title >{{item.menu}}
                  </v-list-item-title>
                </v-list-item>
              </v-list>
            </v-menu>

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
import Body from "@/components/ItemPostList";
import {mdiAccount, mdiPencil, mdiShareVariant, mdiDelete} from "@mdi/js";
import Login from "@/components/Login";
import Register from "@/components/Register";
import axios from 'axios';
import api from "@/config/api";

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
      dialogreg: false,
      itemSubject: [],
      isLogin: false,
      loading: true,
      avatar: 'https://cdn.vuetifyjs.com/images/john.jpg',
      offset: true,
      itemNotification:[],
      menuAvtItems:[{
        menu:'Profile', icon:'mdi-user', link:'/profile'
      }, {menu:'Logout', icon:'mdi-logout', link:'/logout'}],
      query:''
    };
  },
  async created() {
    await axios.get(api.BASE_URL+'subject').then(
        res =>{
          const values = res.data.data;
          values.forEach((value => {
            this.itemSubject.push(value.subject_tag);
          }))
        }
    )
    if (this.$cookie.get('token') !== null) {
      const config = {
        headers: { Authorization: `Bearer `+this.$cookie.get('token') }
      };
      await axios.get(api.ACCOUNT_URL, config).then(
          res => {
            if (res.status === 200) {
              this.isLogin = true
              this.avatar = res.data.user_avatar;
            } else {
              this.$cookie.delete('token');
              this.isLogin = false;
            }
          }
      )
    }
  },
  methods:{
    async loadNotification(){
      const config = {
        headers: { Authorization: `Bearer `+this.$cookie.get('token') }
      };
      await axios.get(api.BASE_URL+'notification', config).then(
          res =>{
            this.itemNotification = res.data.data;
          }
      )
    },
    searchWithQuery(){
      this.$emit('search', this.query);
    }
  }
}
</script>

<style scoped>
.header {
  line-height: 30px;
  background-color: #333;
}
.theme--light.v-input input, .theme--light.v-input textarea {
  color: red!important;
}
</style>