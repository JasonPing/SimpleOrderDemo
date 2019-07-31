<template>
  <div class="userlogin">
    <LoginForm title="User Login" role="user"></LoginForm>
  </div>
</template>

<script>
// @ is an alias to /src
import LoginForm from '@/components/LoginForm.vue'

export default {
  name: 'userLogin',
  components: {
    LoginForm
  }
}
</script>
