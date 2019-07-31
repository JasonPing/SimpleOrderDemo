<template>
<div class="loginForm">
  <form novalidate class="md-layout" @submit.prevent="validateUser">
    <md-card class="md-layout-item">
      <md-card-header>
        <div class="md-title">{{title}}</div>
      </md-card-header>

      <md-card-content>
        <md-field :class="getValidationClass('username')">
          <label for="username">User Name</label>
          <md-input type="username" name="username" id="username" autocomplete="username" v-model="form.username" :disabled="sending" />
          <span class="md-error" v-if="!$v.form.username.required">
            The user name is required
          </span>
          <span class="md-error" v-if="!$v.form.username.maxLength">
            The user name is max 20 characters
          </span>
        </md-field>


        <md-field :class="getValidationClass('password')">
          <label for="password">Password</label>
          <md-input type="password" name="password" id="password" autocomplete="password" v-model="form.password" :disabled="sending" />
          <span class="md-error" v-if="!$v.form.password.required">
            The password is required
          </span>
          <span class="md-error" v-if="!$v.form.password.minLength">
            The password is min 4 characters
          </span>
          <span class="md-error" v-if="!$v.form.password.maxLength">
            The password is max 10 characters
          </span>
        </md-field>

        <label class="error">{{this.errormsg}}</label>
      </md-card-content>

      <md-progress-bar md-mode="indeterminate" v-if="sending" />

      <md-card-actions>
        <md-button type="submit" class="md-raised md-primary" :disabled="sending">Login</md-button>
      </md-card-actions>
    </md-card>

  </form>

</div>
</template>

<script>
import {
  validationMixin
} from 'vuelidate'
import {
  required,
  minLength,
  maxLength
} from 'vuelidate/lib/validators'

import router from '@/router.js'

export default {
  name: 'loginForm',
  mixins: [validationMixin],
  props: ['title','role'],
  data: () => ({
    form: {
      username: null,
      password: null
    },
    userSaved: false,
    sending: false,
    errormsg:''
  }),
  validations: {
    form: {
      username: {
        required,
        maxLength: maxLength(20)
      },
      password: {
        required,
        minLength: minLength(4),
        maxLength: maxLength(10)
      },
    }
  },
  methods: {
    getValidationClass(fieldName) {
      const field = this.$v.form[fieldName]

      if (field) {
        return {
          'md-invalid': field.$invalid && field.$dirty
        }
      }
    },
    clearForm() {
      this.$v.$reset()
      this.form.username = null
      this.form.password = null
    },
    validUser() {
      this.sending = true

      if(this.role == "user") {
        var self = this
        this.axios
          .post(this.$store.state.apiUrl+"/user-login",{
            name: this.form.username,
            password: this.form.password
          })
          .then(function (response) {
            if(response.data.status == "success") {
                localStorage.setItem('token', response.data.api_key)
                self.$store.commit('LOGIN_USER')
                self.userSaved = true
                self.sending = false
                self.username = `${self.form.username}`;
                self.password = `${self.form.password}`;
                self.clearForm();
                var user_id = response.data.user_id;
                router.push('/dashboard/'+user_id);
            } else{
              self.userSaved = false
              self.sending = false
              self.errormsg = response.data.msg
            }
          })
          .catch(function (error) {
            self.userSaved = false
            self.sending = false
            self.errormsg = "Server Error: "+error
          });

      } else if(this.role == "admin")  {
        var self = this
        this.axios
          .post(this.$store.state.apiUrl+"/admin-login",{
            name: this.form.username,
            password: this.form.password
          })
          .then(function (response) {
            if(response.data.status == "success") {
                localStorage.setItem('admintoken', response.data.api_key)
                self.$store.commit('LOGIN_ADMIN')
                self.userSaved = true
                self.sending = false
                self.username = `${self.form.username}`;
                self.password = `${self.form.password}`;
                self.clearForm();
                router.push('/admin-dashboard');
            } else{
              self.userSaved = false
              self.sending = false
              self.errormsg = response.data.msg
            }
          })
          .catch(function (error) {
            self.userSaved = false
            self.sending = false
            self.errormsg = "Server Error: "+error
          });
      }

    },
    validateUser() {
      this.$v.$touch()

      if (!this.$v.$invalid) {
        this.validUser()
      }
    }
  }
}
</script>

<style scoped>
.loginForm {
  margin: 0 auto;
  padding: 50px;
  max-width: 600px;
}
.error {
  color:red
}
</style>
