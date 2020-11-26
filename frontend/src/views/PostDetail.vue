<template>
  <default-layout>
    <v-overlay :value="overlay">
      <v-progress-circular
          indeterminate
          size="64"
      ></v-progress-circular>
    </v-overlay>
    <post v-bind:title.sync="title"/>
    <h1>All comment</h1>
    <div class="comment-list" v-if="!noComment">
      <comment v-for="(object,index ) in dataComment" :key="index" :data-object="object"
               :index-comment="index+1"/>
      <div class="pagination">
        <div class="text-center">
          <v-pagination
              v-model="paging.current_page"
              :length="paging.total"
              @next="nextPage"
              @previous="previousPage"
              @input="inputPage"
              circle
          ></v-pagination>
        </div>
      </div>
    </div>
    <div class="comment-list" v-else style="text-align: center">
      <h2>No comment in post</h2>
    </div>
  </default-layout>
</template>

<script>
import DefaultLayout from "@/components/DefaultLayout";
import Post from "@/components/Post";
import Comment from "@/components/Comment";
import axios from 'axios';
import api from "@/config/api";

export default {
  name: "PostDetail",
  components: {Comment, Post, DefaultLayout},
  data() {
    return {
      dataComment:[],
      noComment:true,
      overlay:true,
      paging:[],
      title:''
    }
  },
  watch: {
    title: {
      immediate: true,
      handler() {
        document.title = this.title;
      },
    },
  },
  async created() {
    if (this.$cookie.get('token') !==null){
      await axios.get(api.COMMENT_URL, {params: {postId: this.$route.params.id},
        headers: { Authorization: `Bearer `+this.$cookie.get('token') }}).then(
          res => {
            if (res.status ===200){
              this.dataComment = res.data.data;
              this.paging = res.data.paging;
              this.overlay = false;
              if (this.dataComment.length>0){
                this.noComment = false;

              }
            }
          });
    }
    else {
      await axios.get(api.COMMENT_URL, {params: {postId: this.$route.params.id}}).then(
          res => {
            if (res.status ===200){
              this.dataComment = res.data.data;
              this.paging = res.data.paging;
              this.overlay = false;
              if (this.dataComment.length>0){
                this.noComment = false;

              }
            }
          });
    }

  },
  methods:{
    async nextPage(){
      this.overlay = true;
      await axios.get(api.COMMENT_URL, {params: {postId: this.$route.params.id,
          page:this.paging.current_page}}).then(
          res => {
            if (res.status ===200){
              this.dataComment = res.data.data;
              this.paging = res.data.paging;
              this.overlay = false;
              console.log(this.dataComment.length);
              if (this.dataComment.length>0){
                this.noComment = false;
              }
            }
          }
      );
    },
    async previousPage(){
      this.overlay = true;
      await axios.get(api.COMMENT_URL, {params: {postId: this.$route.params.id,
          page:this.paging.current_page}}).then(
          res => {
            if (res.status ===200){
              this.dataComment = res.data.data;
              this.paging = res.data.paging;
              this.overlay = false;
              console.log(this.dataComment.length);
              if (this.dataComment.length>0){
                this.noComment = false;
              }
            }
          }
      );
    },
    async inputPage(){
      this.overlay = true;
      await axios.get(api.COMMENT_URL, {params: {postId: this.$route.params.id,
          page:this.paging.current_page}}).then(
          res => {
            if (res.status ===200){
              this.dataComment = res.data.data;
              this.paging = res.data.paging;
              this.overlay = false;
              console.log(this.dataComment.length);
              if (this.dataComment.length>0){
                this.noComment = false;
              }
            }
          }
      );
    },
  }
}
</script>

<style scoped>

</style>