<template>
  <div class="item-list-activity">
    <v-row>
      <v-col cols="1">
        <v-avatar>
          <img
              :src="avatarUser"
              alt="AVT"
          >
        </v-avatar>
      </v-col>
      <v-col cols="9">
        <a v-html="dataComment.comment_content.comment_content_main"></a>
      </v-col>
      <v-col cols="2">
        <code>{{ dataComment.comment_content.comment_content_create }}</code>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "ItemActivityComment",
  props: {
    dataComment: Object,
    avatarUser: String
  }
}
</script>

<style scoped>

</style>