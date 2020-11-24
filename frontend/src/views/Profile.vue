<template>
  <default-layout>

    <v-row>
      <v-col cols="1">
        <v-avatar>
          <img
              :src="dataAccount.user_avatar"
              alt="AVT"
          >
        </v-avatar>
      </v-col>
      <v-col cols="3">
        <h1 style="margin-bottom: 0px;">{{dataAccount.user_full_name}}</h1>
        <h4>{{dataAccount.user_name}}</h4>
        <v-chip v-if="dataAccount.user_permission === 2"
            text-color="white"
            color="green">
          Mod
        </v-chip>
        <v-chip v-else-if="dataAccount.user_permission === 1"
                text-color="white"
                color="red">
          Admin
        </v-chip>
        <v-chip v-else
                text-color="white"
                color="blue">
          Member
        </v-chip>
      </v-col>
    </v-row>
    <v-divider></v-divider>
    <v-row class="action-profile">
      <v-btn-toggle
          v-model="actionProfile"
          tile
          color="deep-purple accent-3"
          group>
        <v-btn value="summary">
          <v-icon left>
            mdi-account-details
          </v-icon>
          Summary
        </v-btn>
        <v-btn value="activity">
          <v-icon left>
            mdi-account-clock
          </v-icon>
          Activity
        </v-btn>
        <v-btn value="notification">
          <v-icon left>
            mdi-bell
          </v-icon>
          Notification
        </v-btn>
        <v-btn value="Preferences">
          <v-icon left>
            mdi-cog
          </v-icon>
          Preferences
        </v-btn>
      </v-btn-toggle>
    </v-row>
    <v-divider ></v-divider>
    <profile-summary v-if="actionProfile === 'summary'"/>
    <profile-activity v-else-if="actionProfile === 'activity'"/>
    <profile-notification v-else-if="actionProfile === 'notification'"/>
    <profile-preferences v-else/>
  </default-layout>
</template>

<script>
import DefaultLayout from "@/components/DefaultLayout";
import ProfileActivity from "@/components/ProfileActivity";
import ProfileNotification from "@/components/ProfileNotification";
import ProfilePreferences from "@/components/ProfilePreferences";
import ProfileSummary from "@/components/ProfileSummary";
import axios from 'axios';
import api from "@/config/api";
export default {
  name: "Profile",
  components: {DefaultLayout, ProfileActivity, ProfileSummary,
    ProfilePreferences,ProfileNotification},
  async created() {
    if (this.$cookie.get('token') !== null){
      const config = {
        headers: {Authorization: `Bearer ` + this.$cookie.get('token')}
      };
      await axios.get(api.ACCOUNT_URL, config).then(
          res =>{
            if (res.status === 200 && res.data.success === true){
              console.log(res.data);
              this.dataAccount = res.data;
            }
          }
      )
    }else{
      await this.$router.push('/');
    }
  },
  watch: {
    title: {
      immediate: true,
      handler() {
        document.title = "Profile - Kami Community";
      },
    },
  },
  data(){
    return({
      actionProfile:'summary',
      dataAccount:[]
    })
  }
}
</script>

<style scoped>

</style>