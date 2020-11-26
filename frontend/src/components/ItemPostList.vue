<template>
    <v-container>
      <component is="style">
        .content > .theme--light.v-icon{
        color: {{color}};
        }
      </component>
        <v-row style="
         border-bottom: groove;
         border-bottom-width: thin;
          ">
          <v-col cols="9">
            <a style="" :href="'/post/'+dataItemPost.post_id">{{dataItemPost.post_content.post_content_title}}</a>
            <div class="content">
              <v-chip
                  :color="color"
              ><h5 style="color: white">{{dataItemPost.subject_short.subject_tag}}</h5></v-chip>
              <v-icon color="#000000" style="margin-left: 1%;">mdi-tag-outline</v-icon>
              <p v-for="index in dataItemPost.post_tag" style="display: inline-flex; margin-left: 3px;">{{index.tag_content}}</p>
            </div>
          </v-col>
          <v-col cols="1">
            {{dataItemPost.total_comment}}
          </v-col>
          <v-col cols="1">
            {{dataItemPost.total_like}}
          </v-col>
          <v-col cols="1">
            {{dataItemPost.last_activity}}h
          </v-col>                        
        </v-row>        
    </v-container>
</template>
<script>
export default {
  name:'ItemPostList',
    data () {
      return {
        color:"#00ff00"
      }
    },
  props:{
    dataItemPost:Object
  },
  created() {
    if(this.dataItemPost.subject_short.subject_color !== null){
      this.color = this.dataItemPost.subject_short.subject_color;
    }
  },
}
</script>
<style scoped>
a{
  text-decoration: none;
}
</style>