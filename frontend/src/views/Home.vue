<template>
  <default-layout @search="search">
    <div class="top-menu">
      <v-row style="float: left; margin-left: 10%; width: 90%;">
        <v-col cols="3">
          <v-overflow-btn
              :items="itemSubject"
              editable
              :disabled="disabledSubject"
              label="Select Subject"
              hide-details
              class="pa-0"
              overflow
              v-model="itemSelect"
              @change="postBySubject"
          ></v-overflow-btn>
        </v-col>
        <v-col cols="6" style="max-width: 100%">
          <v-tabs style="width: auto">
            <v-tab>Top</v-tab>
            <v-tab>All Subject</v-tab>
            <v-tab>Top Comment</v-tab>
          </v-tabs>
        </v-col>
        <v-col cols="3">
          <v-btn
              tile
              @click="openNewPost"
              color="success">
            <v-icon left>
              mdi-plus
            </v-icon>
            NEW POST
          </v-btn>
        </v-col>
      </v-row>
    </div>
    <div class="home">
      <div class="header-topic-list">
        <v-row>
          <v-col cols="9">
            <p>Topic</p>
          </v-col>
          <v-col cols="1">
            <p>Replies</p>
          </v-col>
          <v-col cols="1">
            <p>Like</p>
          </v-col>
          <v-col cols="1">
            <p>Activity</p>
          </v-col>
        </v-row>
        <v-divider></v-divider>
      </div>
      <v-overlay :value="overlay">
        <v-progress-circular
            indeterminate
            size="64"
        ></v-progress-circular>
      </v-overlay>
      <ItemPostList v-for="index in dataPost" :key="index.post_id" :data-item-post="index">
      </ItemPostList>
      <div class="pagination">
        <div class="text-center">
          <v-pagination
              v-model="paging.current_page"
              :length="paging.total"
              @input="nextPage"
              @previous="nextPage"
              @next="nextPage"
              circle
          ></v-pagination>
        </div>
      </div>
      <v-bottom-sheet v-model="sheet">
        <v-sheet class="text-center" height="550px">
          <h3>Create a new post</h3>
          <v-text-field
              v-model="titlePost"
              color="purple darken-2"
              label="Tiêu đề bài viết"
              outlined
          ></v-text-field>

          <editor
              api-key="f8yzzwhskk61fuey3qpoo4qppynflk4o9ho7ntfjyxjame97"
              v-model="dataHtml"
              :init="{
         height: 250,
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
          <v-combobox
              v-model="selectTag"
              :items="allTag"
              label="Choose tag"
              multiple
              chips
          ></v-combobox>
          <v-overflow-btn
              :items="itemSubject"
              editable
              label="Select Subject"
              hide-details
              class="pa-0"
              overflow
              v-model="selectSubject"
          ></v-overflow-btn>
        </v-sheet>
        <v-btn depressed
               color="primary" @click="createPost">
          <v-icon dark small left>mdi-upload</v-icon>
          Create Post
        </v-btn>
      </v-bottom-sheet>
      <v-snackbar v-model="snackbar" timeout="2000">
        {{ textSnackbar }}
        <template v-slot:action="{ attrs }">
          <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
            Close
          </v-btn>
        </template>
      </v-snackbar>
    </div>
  </default-layout>
</template>

<script>
// @ is an alias to /src
import DefaultLayout from "@/components/DefaultLayout";
import ItemPostList from "@/components/ItemPostList";
import axios from 'axios';
import api from "@/config/api";
import Editor from '@tinymce/tinymce-vue';

export default {
  name: 'Home',
  components: {
    ItemPostList,
    DefaultLayout,
    Editor
  },
  watch: {
    title: {
      immediate: true,
      handler() {
        document.title = "Kami Community Home";
      },
    },
  },
  data() {
    return {
      paging: [],
      dataPost: [],
      overlay: true,
      snackbar: false,
      itemSubject: [],
      itemSelect: -1,
      disabledSubject: false,
      sheet: false,
      textSnackbar: '',
      dataHtml: '',
      selectTag:[],
      allTag:[],
      titlePost:'',
      selectSubject:1
    }
  },
  async created() {
    await axios.get(api.BASE_URL + 'subject').then(
        res => {
          const values = res.data.data;
          values.forEach((value => {
            this.itemSubject.push({
              text: value.subject_tag,
              value: value.subject_id
            });
          }))
        }
    );
    await axios.get(api.POST_URL, {params: {page: 1}}).then(
        res => {
          if (res.status === 200) {
            this.dataPost = res.data.data;
            this.overlay = false;
            this.paging = res.data.paging;
          } else {
            this.overlay = false;
            this.snackbar = true;
          }
        }
    );
    await axios.get(api.BASE_URL+'tag').then(
        res =>{
          const data = res.data.data;
          data.forEach((value=>{
            this.allTag.push({
              text:value.tag_content,
              value:value.tag_id
            });
          }));
        }
    )
  },
  methods: {
    async nextPage() {
      this.overlay = true;
      await axios.get(api.POST_URL, {params: {page: this.paging.current_page}}).then(
          res => {
            if (res.status === 200) {
              this.dataPost = res.data.data;
              this.overlay = false;
              this.paging = res.data.paging;
            } else {
              this.overlay = false;
              this.snackbar = true;
            }
          }
      )
    },
    async postBySubject() {
      this.overlay = true;
      this.disabledSubject = true;

      await axios.get(api.POST_URL, {params: {subject: this.itemSelect}}).then(
          res => {
            if (res.status === 200) {
              this.dataPost = res.data.data;
              this.overlay = false;
              this.paging = res.data.paging;
              this.disabledSubject = false;
            } else {
              this.overlay = false;
              this.disabledSubject = false;
              this.snackbar = true;

            }
          });
    },
    openNewPost() {
      if (this.$cookie.get('token') !== null) {
        this.sheet = true;
        this.selectSubject = this.itemSelect;
      } else {
        this.textSnackbar = "Vui lòng đăng nhập để tạo bài viết";
        this.snackbar = true;
      }
    },
    async createPost() {
      console.log(this.selectTag);
      if (this.$cookie.get('token') !== null) {
        await axios({
          method: 'POST',
          url: api.BASE_URL + 'post/',
          headers: {
            Authorization: `Bearer ` + this.$cookie.get('token')
          },
          data: {post_content:this.dataHtml, subject_id:this.selectSubject, post_title:this.titlePost, tag:this.selectTag}
        }).then(
            res => {
              if (res.status === 200){
                this.sheet = false;
                this.textSnackbar = "Tạo bài viết thành công!";
                this.snackbar = true;
              }
            }
        )
      } else {
        this.textSnackbar = "Vui lòng đăng nhập để tạo bài viết";
        this.snackbar = true;
      }
    },
    async search(value){
      this.overlay = true;
      this.disabledSubject = true;
      if (this.itemSelect === -1){
        await axios.get(api.POST_URL, {params: {q:value,
            page: this.paging.current_page}}).then(
            res => {
              if (res.status === 200) {
                this.dataPost = res.data.data;
                this.overlay = false;
                this.paging = res.data.paging;
                this.disabledSubject = false;
              } else {
                this.overlay = false;
                this.disabledSubject = false;
                this.snackbar = true;

              }
            });
      }else {
        await axios.get(api.POST_URL, {params: {subject: this.itemSelect, q:value,
            page: this.paging.current_page}}).then(
            res => {
              if (res.status === 200) {
                this.dataPost = res.data.data;
                this.overlay = false;
                this.paging = res.data.paging;
                this.disabledSubject = false;
              } else {
                this.overlay = false;
                this.disabledSubject = false;
                this.snackbar = true;

              }
            });
      }

    }
  }
}
</script>
