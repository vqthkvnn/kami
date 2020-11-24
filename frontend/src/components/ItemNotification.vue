<template>
<div class="item-notification">
  <v-row>
    <v-col cols="1">
      <v-icon v-if="notificationType === 1">
        mdi-reply-outline
      </v-icon>
      <v-icon v-else-if="notificationType === 2">
        mdi-thumb-up
      </v-icon>
      <v-icon v-else-if="notificationType === 3">
        mdi-square-edit-outline
      </v-icon>


    </v-col>
    <v-col cols="2">
      <code style="margin-left: 5%;">{{dataNotification.notification_date}}</code>
    </v-col>
    <v-col cols="8">
      <p >{{dataNotification.notification_content}}</p>
    </v-col>
  </v-row>
</div>
</template>

<script>
export default {
  name: "ItemNotification",
  props:{
    dataNotification:Object
  },
  data() {
    return {
      notificationType:3
    }
  }
}
</script>

<style scoped>

</style>