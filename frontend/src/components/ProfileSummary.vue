<template>
  <div class="profile-summary" >
    <v-overlay :value="overlay">
      <v-progress-circular
          indeterminate
          size="64"
      ></v-progress-circular>
    </v-overlay>
    <v-row>
      <v-col cols="2">{{dataSummary.post_created}} Post created</v-col>
      <v-col cols="3">{{dataSummary.comment_created}}
        <v-icon>mdi-comment-edit</v-icon>
        Comment created</v-col>
      <v-col cols="2">{{dataSummary.like_given}}
      <v-icon>
        mdi-heart
      </v-icon>
        Given
      </v-col>
      <v-col cols="2">
        {{dataSummary.total_like_comment + dataSummary.total_like_post}}
        <v-icon>
          mdi-heart
        </v-icon>
        Received
      </v-col>
    </v-row>
    <v-divider></v-divider>
    <v-row>
      <v-col cols="5">
        <h1>NEW TOPICS</h1>
        <div class="top-topic-list" v-for="(item, index) in dataSummary.top_post" :key="index">
          <v-divider></v-divider>
          <p>{{item.post_content.post_content_create}}</p>
          <h4 v-html="item.post_content.post_content_title"></h4>
        </div>
      </v-col>
     <v-col cols="1">
       <v-divider
           vertical
       ></v-divider>
     </v-col>
      <v-col cols="5">
        <h1>NEW COMMENT</h1>
        <div class="top-comment-list" v-for="(item, index) in dataSummary.top_comment" :key="index">
          <v-divider></v-divider>
          <p>{{item.comment_date}}</p>
          <h4 v-html="item.comment_content.comment_content_main"></h4>
        </div>
      </v-col>

    </v-row>
    <v-divider></v-divider>
<!--    <v-row>-->
<!--      <h1>TOP CATEGORIES</h1>-->
<!--    </v-row>-->
  </div>
</template>

<script>
import axios from 'axios';
import api from "@/config/api";
export default {
  name: "ProfileSummary",
  data(){
    return{
      overlay:true,
      dataSummary:[]
    }
  },
  async created() {
    this.overlay = true;
    if (this.$cookie.get('token') !== null) {
      const config = {
        headers: {Authorization: `Bearer ` + this.$cookie.get('token')}
      };
      await axios.get(api.ACCOUNT_URL+'summary', config).then(
          res =>{
            if (res.status === 200 && res.data.success === true){
              this.dataSummary = res.data.data;
              this.overlay = false
            }
          });

    }else {
      this.overlay = false;
    }
  }

}
</script>

<style scoped>

</style>