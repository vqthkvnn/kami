<template>
    <v-container>
        <v-row>
          <h1 class="toppic" style="font-size:1.7511em;line-height:1.2;">
            Countdown timer
          </h1>
        </v-row>
        <v-row style="
         border-bottom: groove;
         border-bottom-width: thin;   
      ">
        <v-col cols="1">
          <div class="pa-2 yellow rounded-circle d-inline-block " style="float:left;margin-right:10px;"> 
         </div>
         <p>PHP</p>
        </v-col>
        </v-row>
        <v-row>
          <v-col cols="1">
            <v-img
              height="45"
              width="45"
              lazy-src="https://picsum.photos/id/11/10/6"
              src="https://picsum.photos/id/11/500/300"
              style="border-radius:100%">
            </v-img>
          </v-col>
          <v-col cols="3">
            lurtnowski
          </v-col>
           <v-col cols="6">
          </v-col>
          <v-col cols="1" style="font-size:15px;color:#999;">
              1h
          </v-col>
          <v-col cols="1" style="font-size:15px;color:#999;">
              #1
          </v-col>                        
        </v-row>
      <v-row>
        <v-col cols="1">
        </v-col>
        <v-col cols="11">
            <p>1</p>
            <p>2</p>
            <p>3</p>
            <p>4</p>
            <p>4</p>
            <p>5</p>


        </v-col>
      </v-row>
      <v-row>
        <v-col cols="10">

        </v-col>
        <v-col cols="1">
          <a href="">
            <i class="far fa-heart fa-2x"></i>
          </a>          
        </v-col>
        <v-col cols="1">
          <a href="">
            <i class="fas fa-share-alt-square fa-2x" style="width:20px;"></i>
          </a>          
        </v-col>
      </v-row>
      <v-row>
        <v-col cols="1">

        </v-col>
        <v-col cols="11"
        style="border-style:ridge;background-color: #f9f9f9;"
        >
            <v-row>
              <v-col cols="1">
                <h1 style="font-size:14px;color:#999;">
                 created
                </h1> 
              </v-col>
              <v-col cols="1">
                <h1 style="font-size:14px;color:#999;">
                 last reply
                </h1> 
              </v-col>
              <v-col cols="1">
                12
              </v-col>
              <v-col cols="1">
                205
              </v-col>
              <v-col cols="1">
                8
              </v-col>
              <v-col cols="1">
                9
              </v-col>
              <v-col cols="6">
                
              </v-col>              
            </v-row>
            <v-row>
              <v-col cols="1">
                <v-img
                height="30"
                width="30"
                  lazy-src="https://picsum.photos/id/11/10/6"
                  src="https://picsum.photos/id/11/500/300"
                  style="border-radius:100%;float:left;position: relative;margin-right: 10px;bottom:10px;">
                </v-img>
                <div>10d</div>
              </v-col>
              <v-col cols="1">
                <v-img
                height="30"
                width="30"
                  lazy-src="https://picsum.photos/id/11/10/6"
                  src="https://picsum.photos/id/11/500/300"
                  style="border-radius:100%;float:left;position: relative;margin-right: 10px;bottom:10px;">
                </v-img>
                <div >24h</div>
              </v-col>      
              <v-col cols="1">
                 <h1 style="font-size:15px;color:#999;">
                 replies
                </h1>                
              </v-col>    
              <v-col cols="1">
                 <h1 style="font-size:15px;color:#999;">
                 views
                </h1>                
              </v-col> 
              <v-col cols="1">
                 <h1 style="font-size:15px;color:#999;">
                 users
                </h1>                
              </v-col>  
              <v-col cols="1">
                 <h1 style="font-size:15px;color:#999;">
                 likes
                </h1>                
              </v-col>                                                             
            </v-row>
            
        </v-col>
      </v-row>
    </v-container>
</template>
<script>
export default {
  name:'Post',
    data () {
      return {
       
      }
    },
}
</script>