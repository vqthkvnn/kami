<template>
  <div class="profile-notification">
    <v-overlay :value="overlay">
      <v-progress-circular
          indeterminate
          size="64"
      ></v-progress-circular>
    </v-overlay>
    <v-row>
      <v-col cols="2">
        <v-list flat>
          <v-subheader>Select</v-subheader>
          <v-list-item-group
              v-model="selectedItem"
              color="primary"
          >
            <v-list-item
                v-for="(item, i) in items"
                :key="i"
            >
              <v-list-item-content>
                <v-list-item-title v-text="item"></v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list-item-group>
        </v-list>
      </v-col>
      <v-divider
          vertical
      ></v-divider>
      <v-col cols="9" style="margin-left: 10px;">
        <item-notification v-for="(item, index) in dataNotification" :key="index" :data-notification="item"/>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import ItemNotification from "@/components/ItemNotification";
import axios from 'axios';
import api from "@/config/api";
export default {
  name: "ProfileNotification",
  components: {ItemNotification},
  data() {
    return {
      overlay: true,
      selectedItem: 0,
      items: ['All', 'Responses', 'Likes', 'Edit'],
      dataNotification:[]
    }
  },
  async created() {
    if (this.$cookie.get('token') !== null){
      const config = {
        headers: {Authorization: `Bearer ` + this.$cookie.get('token')}
      };
      await axios.get(api.BASE_URL+'notification', config).then(
          res =>{
            if (res.status ===200){
              this.dataNotification = res.data.data;
              this.overlay = false;
            }else {
              this.overlay = false;
            }
          }
      )
    }else {
      this.overlay = false;
    }
  }
}
</script>

<style scoped>

</style>