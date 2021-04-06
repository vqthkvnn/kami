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
        <a>{{ dataItem.post_content.post_content_title }}</a>
        <v-row>
          <v-chip :color="dataItem.subject_short.subject_color">{{ dataItem.subject_short.subject_tag }}</v-chip>
          <div style="margin-left: 1%">
            <v-icon>
              mdi-tag-outline
            </v-icon>
            <p v-for="(data, index) in dataItem.post_tag" :key="index" style="display: inline-flex; margin-right: 5px;">
              {{ data.tag_content }}</p>
          </div>
        </v-row>
      </v-col>
      <v-col cols="2">
        <code>{{ dataItem.post_content.post_content_create }}</code>
      </v-col>
    </v-row>
  </div>
</template>

<script>
export default {
  name: "ItemActivity",
  props: {
    dataItem: Object,
    avatarUser: String,
  },
  data() {
    return {}
  },
  created() {

  }
}
</script>

<style scoped>

</style>