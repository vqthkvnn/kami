<template>
  <v-container>
    <v-row>
      <h1 class="topic" style="font-size:1.7511em;line-height:1.2;">
        {{ dataPost.post_content_full.post_content_title }}
      </h1>
    </v-row>
    <v-row style="
         border-bottom: groove;
         border-bottom-width: thin;">
      <v-col cols="10" style="display: inline-flex;">
        <v-chip
            :color="color">
          <h5 style="color: white;">{{ dataPost.subject_short.subject_tag }}</h5></v-chip>
        <div class="tag-post" style="margin-left: 1%; margin-top: 3px;">
          <v-icon>
            mdi-tag-outline
          </v-icon>
          <p v-for="index in dataPost.post_tag" style="margin-left: 5px; display: inline-flex;"
             :key="index.tag_content">
            {{ index.tag_content }}</p>
        </div>
      </v-col>
      <v-col cols="2" v-if="dataPost.owner">
        <v-btn fab dark small color="red">
          <v-icon dark small>mdi-square-edit-outline</v-icon>
        </v-btn>
        <v-btn fab dark small style="margin-left: 1%" color="green" @click="deletePost">
          <v-icon dark small>mdi-delete</v-icon>
        </v-btn>
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="1">
        <v-img
            height="45"
            width="45"
            :lazy-src="dataPost.user_avatar"
            :src="dataPost.user_avatar"
            style="border-radius:100%">
        </v-img>
      </v-col>
      <v-col cols="3" style="font-weight: bold;font-size: 18px;">
        <a>{{ dataPost.user_name }}</a>
        <v-icon style="margin-left: 2%" dark small color="pink" v-if="dataPost.user_permission === 2">
          mdi-security
        </v-icon>
        <v-icon style="margin-left: 2%" dark small color="pink" v-if="dataPost.user_permission === 1">
          mdi-shield-account
        </v-icon>

        <v-icon v-else></v-icon>
      </v-col>
      <v-col cols="5">
      </v-col>
      <v-col cols="2" style="font-size:15px;color:#999;">
        {{ dataPost.post_content_full.post_content_create }}
      </v-col>
      <v-col cols="1" style="font-size:15px;color:#999;">
        #1
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="1">
      </v-col>
      <v-col cols="11" v-html="dataPost.post_content_full.post_content_main">
      </v-col>
    </v-row>
    <v-row>
      <v-col cols="10">
      </v-col>
      <v-col cols="2">
        <v-badge style="margin-right: 5%"
                 color="green"
                 :content="totalLike">
          <v-btn fab dark small :color="isLike" @click="likePostMethod">
            <v-icon dark>
              mdi-heart
            </v-icon>
          </v-btn>
        </v-badge>

        <v-btn class="mx-2" fab dark small @click="openCreateComment" color="primary">
          <v-icon dark>
            mdi-reply-all
          </v-icon>
        </v-btn>
        <v-bottom-sheet v-model="sheet">
          <v-sheet class="text-center" height="400px">
            <h3>Write comment of post</h3>
            <editor
                api-key="f8yzzwhskk61fuey3qpoo4qppynflk4o9ho7ntfjyxjame97"
                v-model="dataHtml"
                :datasrc="dataSrcEdit"
                :init="{
         height: 300,
         menubar: false,
         plugins: [
           'advlist autolink lists link image charmap print preview anchor',
           'searchreplace visualblocks code fullscreen',
           'insertdatetime media table paste code help wordcount', 'code'
         ],
         toolbar:
           'undo redo | formatselect | bold italic backcolor | \
           alignleft aligncenter alignright alignjustify | \
           bullist numlist outdent indent | removeformat | help | code'
       }"
            />
          </v-sheet>
          <v-btn depressed
                 color="primary" @click="createComment">
            <v-icon dark small left>mdi-upload</v-icon>
            Replay Post
          </v-btn>
        </v-bottom-sheet>
      </v-col>
    </v-row>
    <v-snackbar v-model="snackbar" :timeout="timeoutSnackbar">
      {{ textSnackbar }}
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </v-container>
</template>
<script>
import axios from 'axios';
import api from "@/config/api";
import Editor from '@tinymce/tinymce-vue';

export default {
  name: 'Post',
  components: {Editor},
  data() {
    return {
      dataPost: [],
      dataComment: [],
      overloadPost: false,
      overloadComment: true,
      color: "#00ff00",
      sheet: false,
      textSnackbar: '',
      dataHtml: '',
      dataSrcEdit:'',
      snackbar: false,
      timeoutSnackbar: 2000,
      totalLike: '0',
      isLike: '#000000',
      isLikePost: false
    }
  },
  props:{
    titleHeader:String
  },
  async created() {
    if (this.$cookie.get('token') !== null) {
      await axios.get(api.POST_URL + "/" + this.$route.params.id,
          {headers: {Authorization: `Bearer ` + this.$cookie.get('token')}}).then(
          res => {
            if (res.status === 200) {
              this.dataPost = res.data.data;
              this.titleHeader = this.dataPost.post_content_full.post_content_title;
              this.$emit('update:title', this.titleHeader);
              if (this.dataPost.subject_short.subject_color !== null) {
                this.color = this.dataPost.subject_short.subject_color;
              }
            } else {
              this.overloadPost = true;
            }
          }
      );
    } else {
      await axios.get(api.POST_URL + "/" + this.$route.params.id).then(
          res => {
            if (res.status === 200) {
              this.dataPost = res.data.data;
              if (this.dataPost.subject_short.subject_color !== null) {
                this.color = this.dataPost.subject_short.subject_color;
              }
            } else {
              this.overloadPost = true;
            }
          }
      );
    }

    // total like
    await axios.get(api.BASE_URL + 'postVote/' + this.$route.params.id).then(
        res => {
          if (res.status === 200 && res.data.total > 0) {

            this.totalLike = res.data.total;
          }
        }
    );
    // check like when login
    if (this.$cookie.get('token') !== null) {
      const config = {
        headers: {Authorization: `Bearer ` + this.$cookie.get('token')}
      };
      await axios.get(api.BASE_URL + 'like/post/' + this.$route.params.id, config).then(
          res => {
            if (res.status === 200 && res.data.isLike === true) {
              this.isLike = 'pink';
              this.isLikePost = true;
            }
          else {
            this.isLike = '#000000';
            }});
    }

  },
  methods: {
    openCreateComment() {
      if (this.$cookie.get('token') !== null) {
        this.sheet = true;
      } else {
        this.textSnackbar = "Vui lòng đăng nhập để viết bình luận";
        this.snackbar = true;
      }
    },
    async createComment() {
      if (this.$cookie.get('token') !== null) {
        await axios({
          method: 'POST',
          url: api.BASE_URL + 'comment/',
          headers: {
            Authorization: `Bearer ` + this.$cookie.get('token')
          },
          data:{post_id:this.$route.params.id, comment_content:this.dataHtml}
        }).then(
            res =>{
              if (res.status === 200 && res.data.success === true){
                this.sheet = false;
                this.textSnackbar = "Viết bình luận thành công!";
                this.snackbar = true;
              }else{
                this.sheet = false;
                this.textSnackbar = "Lỗi khi viết bình luận!";
                this.snackbar = true;
              }
            }
        )
      } else {
        this.textSnackbar = "Vui lòng đăng nhập để tiếp tục";
        this.snackbar = true;
      }
    },
    async likePostMethod() {
      if (this.$cookie.get('token') !== null) {
        const config = {
          headers: {Authorization: `Bearer ` + this.$cookie.get('token')}
        };
        if (this.isLikePost === true) {
          await axios.delete(api.BASE_URL + 'postVote/' + this.$route.params.id, config).then(
              res => {
                if (res.status === 200 && res.data.success === true) {
                  this.textSnackbar = "Hủy yêu thích thành công";
                  this.snackbar = true;
                  this.isLikePost = false;
                  this.isLike = "#000000";
                  let likeTotal = Number.parseInt(this.totalLike)-1;
                  this.totalLike = likeTotal.toString();
                } else {
                  this.textSnackbar = "Lỗi server";
                  this.snackbar = true;
                }
              }
          );
        } else {
          await axios({
            method: 'POST',
            url: api.BASE_URL + 'postVote/' + this.$route.params.id,
            headers: {
              Authorization: `Bearer ` + this.$cookie.get('token')
            }
          }).then(
              res => {
                      if (res.status === 200 && res.data.success === true) {
                        this.textSnackbar = "Yêu thích thành công";
                        this.snackbar = true;
                        this.isLikePost = true;
                        this.isLike = 'pink';
                        let likeTotal = Number.parseInt(this.totalLike)+1;
                        this.totalLike = likeTotal.toString();
                      } else {
                        this.textSnackbar = "Lỗi server";
                        this.snackbar = true;
                      }
                    }
          );
        }
      } else {
        this.textSnackbar = "Vui lòng đăng nhập để tiếp tục";
        this.snackbar = true;
      }
    },
    async deletePost() {
      if (this.$cookie.get('token')!==null){
        const config = {
          headers: {Authorization: `Bearer ` + this.$cookie.get('token')}
        };
        this.textSnackbar = "Đang xóa bài viết ...";
        this.snackbar = true;
        await axios.delete(api.POST_URL+'/'+this.$route.params.id, config).then(
            res =>{
              if (res.status === 200 && res.data.success === true){
                this.snackbar = false;
                this.textSnackbar = "Xóa thành công";
                this.snackbar = true;
                setTimeout(()=>this.$router.push('/'), 1000);
              }
              else {
                this.snackbar = false;
                this.textSnackbar = "Lỗi Server";
                this.snackbar = true;
              }
            }
        )
      }else {
        this.textSnackbar = "Vui lòng đăng nhập để tiếp tục";
        this.snackbar = true;
      }
    },
  },
}
</script>
<style scoped>
.col-1-information {
  max-width: 10% !important;
}
</style>