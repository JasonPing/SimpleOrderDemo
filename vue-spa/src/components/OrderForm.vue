<template>
<div class="orderForm">
  <form novalidate class="md-layout" @submit.prevent="validateOrder">
    <md-card class="md-layout-item">
      <md-card-header>
        <div class="md-title">{{title}}</div>
      </md-card-header>

      <md-card-content>
        <md-field :class="getValidationClass('o_title')">
          <label for="o_title">Order title</label>
          <md-input type="o_title" name="o_title" id="o_title" v-model="form.o_title" :disabled="sending" />
          <span class="md-error" v-if="!$v.form.o_title.required">
            The Order title is required
          </span>
          <span class="md-error" v-if="!$v.form.o_title.minLength">
            The Order title is min 3 characters
          </span>
          <span class="md-error" v-if="!$v.form.o_title.maxLength">
            The Order title is max 20 characters
          </span>
        </md-field>

        <md-field :class="getValidationClass('o_description')">
          <label for="o_description">Order Description</label>
          <md-textarea  type="o_description" name="o_description" id="o_description" v-model="form.o_description" :disabled="sending"></md-textarea>
          <span class="md-error" v-if="!$v.form.o_description.required">
            The Order description is required
          </span>
          <span class="md-error" v-if="!$v.form.o_description.maxLength">
            The Order description is max 250 characters
          </span>
        </md-field>

        <div class="success">{{success}}</div>
        <div class="error">{{error}}</div>
      </md-card-content>

      <md-progress-bar md-mode="indeterminate" v-if="sending" />

      <md-card-actions>
        <md-button type="submit" class="md-raised md-primary" :disabled="sending">Submit</md-button>
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


export default {
  name: 'loginForm',
  mixins: [validationMixin],
  props: ['title'],
  data: () => ({
    form: {
      title: null,
      o_description: null
    },
    sending: false,
    success:null,
    error:null
  }),
  validations: {
    form: {
      o_title: {
        required,
        minLength: minLength(3),
        maxLength: maxLength(20)
      },
      o_description: {
        required,
        maxLength: maxLength(250)
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
      this.form.o_title = null
      this.form.o_description = null
    },
    validOrder() {
      this.sending = true

      let self = this
      var token = localStorage.getItem("token")
      this.axios
        .post(this.$store.state.apiUrl+"/orders",{
          o_title: this.form.o_title,
          o_description: this.form.o_description,
          user_id:self.$route.params.id
        },{
          headers: {
            Authorization: "Bearer "+token
          }
        })
        .then(function (response) {
          if(response.data.status == "success") {
            self.clearForm()
            self.error = null;
            self.success = response.data.msg;
          } else{
            self.success = null
            self.error = response.data.msg;
          }
          self.sending = false
        })
        .catch(function (error) {
          self.success = null
          self.error = "Server error: "+error;
          self.sending = false
        });


    },
    validateOrder() {
      this.$v.$touch()

      if (!this.$v.$invalid) {
        this.validOrder()
      }
    }
  }
}
</script>

<style scoped>
.orderForm {
  margin: 0 auto;
  padding: 50px;
  max-width: 600px;
}

.success {
  color:green;
}

.error {
  color:red
}
</style>
