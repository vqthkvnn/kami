<template>
    <v-container>
        <v-row>
            <v-col cols="2">
             <v-combobox
                 v-model="select"
                    :items="items"
                    multiple
                    outlined
                    dense
                >
             </v-combobox>
            </v-col>
            <v-col cols="4">
              <v-btn
                depressed
                large
                style="background-color:crimson;color:white;font-family:inherit;"
              >
                Latest
              </v-btn>  
              <v-btn
                depressed
                large
                outlined
                style="font-family:inherit;"
              >
                Categories
              </v-btn>  
              <v-btn
                depressed
                large
                outlined
                style="font-family:inherit;"
              >
                TOP
              </v-btn>  
            </v-col>
        </v-row>
        <v-row style="
        border-bottom: black;
         border-bottom: groove;
         border-bottom-width: unset;
          ">
          <v-col cols="9">
            Topic
          </v-col>
          <v-col cols="1">
            Replies
          </v-col>
          <v-col cols="1">
            Views
          </v-col>
          <v-col cols="1">
            Activity
          </v-col>                        
        </v-row>
        <v-row style="
         border-bottom: groove;
         border-bottom-width: thin;
          ">
          <v-col cols="9">
            <p>How to know if my website’s structure is perfect?</p>
            <div class="contentt">
             <v-icon> fas fa-circle</v-icon>   HTML & CSS
            </div>
          </v-col>
          <v-col cols="1">
            4
          </v-col>
          <v-col cols="1">
            100
          </v-col>
          <v-col cols="1">
            18m
          </v-col>                        
        </v-row>        
    </v-container>
</template>
<script>
export default {
    data () {
      return {
        select: ['All categories'],
        items: [
          'Programming',
          'Design',
          'Vue',
          'Vuetify',
        ],
      }
    },
}
</script>