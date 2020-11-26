<template>
  <validation-observer
    ref="observer"
    v-slot="{ }"
  >
  <div class="container">
      <form 
      @submit.prevent="submit"
      >
      
      <validation-provider
        v-slot="{ errors }"
        name="Name"
        rules="required|min:6"
      >
        <v-text-field
          v-model="user_name"
          :counter="10"
          :error-messages="errors"
          label="User Name"
          required
        ></v-text-field>
      </validation-provider>
      <validation-provider
        v-slot="{ errors }"
        name="email"
        rules="required|email"
      >
        <v-text-field
          v-model="email"
          :error-messages="errors"
          label="E-mail"
          required
        ></v-text-field>
      </validation-provider>
      <validation-provider
        v-slot="{ errors }"
        name="Password1"
        rules="required"
      >
        <v-text-field
          v-model="password"
          :error-messages="errors"
          label="Password"
          :append-icon="show1 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show1 ? 'text' : 'password'"
            name="input-10-1"
            hint="At least 8 characters"
            counter
            @click:append="show1 = !show1"
          required
        ></v-text-field>
      </validation-provider>
      <validation-provider
        v-slot="{ errors }"
        name="Repassword"
        rules=""
        
        
      >
        <v-text-field
          v-model="repassword"
          :error-messages="errors"
          label="Repassword"
          :append-icon="show2 ? 'mdi-eye' : 'mdi-eye-off'"
            :rules="[rules.required, rules.min]"
            :type="show2 ? 'text' : 'password'"
            name="input-10-1"
            hint="At least 8 characters"
            counter
            @click:append="show2 = !show2"
          required
        ></v-text-field>
      </validation-provider>
      <v-btn
        class="mr-4"
        type="submit"
        depressed
        color="primary"
        @click="submit"
      >
        SIGN UP
      </v-btn>
      <v-btn @click="clear" depressed style="color: white"
             color="red">
        CLEAR
      </v-btn>
    </form>
  </div>
    <v-snackbar v-model="snackbar" timeout="1500">
      {{ textSnackbar }}
      <template v-slot:action="{ attrs }">
        <v-btn color="pink" text v-bind="attrs" @click="snackbar = false">
          Close
        </v-btn>
      </template>
    </v-snackbar>
  </validation-observer>
</template>

<script>
import axios from 'axios';
  import { required, email, max } from 'vee-validate/dist/rules';
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
import api from "@/config/api";
  setInteractionMode('eager')
  extend('required', {
    ...required,
    message: '{_field_} can not be empty',
  })
  extend('max', {
    ...max,
    message: '{_field_} may not be greater than {length} characters',
  })
  extend('email', {
    ...email,
    message: 'Email must be valid',
  }) 

  export default {
    name:'Register',
    components: {
      ValidationProvider,
      ValidationObserver,
    },
    data: () => ({
      user_name: '',
      email: '',
      select: null,
      snackbar:'',
      textSnackbar:'',
      items: [
        'Item 1',
        'Item 2',
        'Item 3',
        'Item 4',
      ],
      checkbox: null,
      show1:false,
      show2:false,
      password:'',
      repassword:'',
      rules: {
          //equalpass:value=> password !== value || 'Password error equal',
          required: value => !!value || 'Required.',
          min: v => v.length >= 6 || 'Min 8 characters',
          emailMatch: () => (`The email and password you entered don't match`),
      },
    }),
    methods: {
      async submit () {
        this.$refs.observer.validate();
        await axios.post(api.BASE_URL+'signup', {
          user_name:this.user_name,
          user_email:this.email,
          password:this.password,
        }).then(
            res=>{
              if (res.status ===200){
                this.textSnackbar = "Đăng kí thành công ...";
                this.snackbar = true;
                setTimeout(()=>location.reload(), 1000);

              }
              else{
                this.textSnackbar = "Đăng kí không thành công do lỗi hệ thống";
                this.snackbar = true;
              }
            }
        )
      },
      clear () {
        console.log(this.name)
        this.name = ''
        this.email = ''
        this.select = null
        this.checkbox = null
        this.password=''
        this.repassword=''
        this.$refs.observer.reset()
      },
    },
  }
</script>
