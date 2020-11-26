<template>
  <validation-observer
      ref="observer"
      v-slot="{ }"
  >
    <div class="container">
      <form @submit.prevent="submit">

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
            name="Password"
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
        <v-btn style="margin-left: 50%"
               class="mr-4"
               depressed
               color="primary"
               type="submit"
               @click="">
          Login
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
import {required, email, max} from 'vee-validate/dist/rules';
import {extend, ValidationObserver, ValidationProvider, setInteractionMode} from 'vee-validate'
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
  name: 'Login',
  components: {
    ValidationProvider,
    ValidationObserver,
  },
  props:{
    dialoglog:Boolean
  },
  data: () => ({
    email: '',
    show1: false,
    checkbox: null,
    password: '',
    rules: {
      //equalpass:value=> password !== value || 'Password error equal',
      required: value => !!value || 'Required.',
      min: v => v.length >= 6 || 'Min 6 characters',
      emailMatch: () => (`The email and password you entered don't match`),
    },
    textSnackbar: '',
    snackbar: false,
  }),
  methods: {
    async submit() {
      this.$refs.observer.validate();
      await axios.post(api.BASE_URL + "login", {user_email: this.email, password: this.password}).then(
          res => {
            this.$cookie.set('token', res.data.access_token, 1);
            this.textSnackbar = "Đăng nhập thành công...";
            this.snackbar = true;
            this.dialoglog = false;
            setTimeout(()=>location.reload(), 1000);

          }
      );
    },
    clear() {
      this.name = ''
      this.email = ''
      this.select = null
      this.checkbox = null
      this.password = ''
      this.repassword = ''
      this.$refs.observer.reset()
    },
  },
}
</script>
