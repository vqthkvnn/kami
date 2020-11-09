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
        rules="required|max:10"
      >
        <v-text-field
          v-model="name"
          :counter="10"
          :error-messages="errors"
          label="Name"
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
        @click="checkpass"
      >
        OK
      </v-btn>
      <v-btn @click="clear">
        clear
      </v-btn>
    </form>
  </div>
  </validation-observer>
</template>

<script>
  import { required, email, max } from 'vee-validate/dist/rules'
  import { extend, ValidationObserver, ValidationProvider, setInteractionMode } from 'vee-validate'
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
      name: '',
      email: '',
      select: null,
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
          min: v => v.length >= 8 || 'Min 8 characters',
          emailMatch: () => (`The email and password you entered don't match`),
      },
    }),
    methods: {
      submit () {
        this.$refs.observer.validate()
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
      checkpass(){
        if (this.repassword!==this.password){
          console.log("error")
        }
        
      },
    },
  }
</script>
