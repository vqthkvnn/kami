<template>
  <div class="profile-activity">
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
              @change="loadData"
              color="primary">
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
      <v-col cols="9.5">
        <item-activity v-if="dataActivity  !== null" v-for="(item, index) in dataActivity" :key="index" :data-item="item" :avatar-user="avatar"/>
        <item-activity-comment v-if="dataComment !== null" v-for="(item, index) in dataComment" :key="index" :data-comment="item" :avatar-user="avatar"/>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import ItemActivity from "@/components/ItemActivity";
import axios from 'axios';
import api from "@/config/api";
import ItemActivityComment from "@/components/ItemActivityComment";

export default {
  name: "ProfileActivity",
  components: {ItemActivityComment, ItemActivity},
  data() {
    return {
      overlay: true,
      selectedItem: 0,
      items: ['All', 'Topics', 'Comments', 'Likes', 'Bookmarks', 'Views'],
      dataActivity: [],
      avatar: '',
      dataComment:[]
    }
  },
  async created() {
    this.overlay = true;
    if (this.$cookie.get('token') !== null) {
      const config = {
        headers: {Authorization: `Bearer ` + this.$cookie.get('token')},
        param: {}
      };
      await axios.get(api.ACCOUNT_URL + 'activity', config).then(
          res => {
            if (res.status === 200 && res.data.success === true) {
              this.dataActivity = res.data.data_post;
              this.avatar = res.data.user_avatar;
              this.overlay = false;
            } else {
              this.overlay = false;
            }
          }
      )
    } else {
      this.overlay = false;
    }
  },
  methods: {
    async loadData() {

      if (this.$cookie.get('token') !== null) {
        this.overlay = true;
        const config = {
          headers: {Authorization: `Bearer ` + this.$cookie.get('token')},
          param: {}
        };
        await axios.get(api.ACCOUNT_URL + 'activity', {
          headers:{Authorization: `Bearer ` + this.$cookie.get('token')},
          params:{key:this.selectedItem}
        }).then(
            res => {
              if (res.status === 200 && res.data.success === true) {
                this.dataActivity = res.data.data_post;
                this.dataComment = res.data.data_comment;
                this.avatar = res.data.user_avatar;
                this.overlay = false;
              } else {
                this.overlay = false;
              }
            }
        )
      }
    }

  }
}
</script>

<style scoped>

</style>