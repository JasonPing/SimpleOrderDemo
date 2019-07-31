<template>
  <div class="adminlogin">
    <LoginForm title="Admin Login" role="admin"></LoginForm>
  </div>
</template>

<script>
// @ is an alias to /src
import LoginForm from '@/components/LoginForm.vue'

export default {
  name: 'adminLogin',
  components: {
    LoginForm
  }
}
</script>
