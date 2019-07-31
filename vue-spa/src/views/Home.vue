<template>
  <div class="home">
    <h1 class="md-display-3">Welcome to Simple Order system</h1>
    <h2>There are two default users whose credentials are:</h2>
    <p>Username: user1</p>
    <p>Password: user1</p>
    <br/>
    <p>Username: user2</p>
    <p>Password: user2</p>
    <h2>Admin system url and credentials are:</h2>
    <p>Url: /admin-login</p>
    <p>Username: admin1</p>
    <p>Password: admin1</p>
    <br/>
    <router-link to="/user-login">
        <md-button class="md-raised md-primary">User Login</md-button>
    </router-link>
  </div>
</template>

<script>

export default {
  name: 'home',
}
</script>

<style scoped>
 .home {
   text-align:center
 }
</style>
