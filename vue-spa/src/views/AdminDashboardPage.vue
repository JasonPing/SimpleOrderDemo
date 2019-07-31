<template>
  <div class="orderDashboard">
    <md-app>
      <md-app-toolbar class="md-accent" md-elevation="0">
        <md-button class="md-icon-button" @click="toggleMenu" v-if="!menuVisible">
          <md-icon>menu</md-icon>
        </md-button>
        <span class="md-title">Admin System</span>
      </md-app-toolbar>

      <md-app-drawer :md-active.sync="menuVisible" md-persistent="mini">
        <md-toolbar class="md-transparent" md-elevation="0">
          <span>Navigation</span>

          <div class="md-toolbar-section-end">
            <md-button class="md-icon-button md-dense" @click="toggleMenu">
              <md-icon>keyboard_arrow_left</md-icon>
            </md-button>
          </div>
        </md-toolbar>

        <md-list>

          <md-list-item @click="switchView('home')">
            <md-icon>home</md-icon>
            <span class="md-list-item-text">Home</span>
          </md-list-item>


          <md-list-item @click="switchView('order-list')">
            <md-icon>list</md-icon>
            <span class="md-list-item-text">All Orders</span>
          </md-list-item>

          <md-list-item @click="switchView('user-list')">
            <md-icon>assignment_ind</md-icon>
            <span class="md-list-item-text">All Users</span>
          </md-list-item>

          <md-list-item @click="logout()">
            <md-icon>link_off</md-icon>
            <span class="md-list-item-text">Logout</span>
          </md-list-item>

        </md-list>
      </md-app-drawer>

      <md-app-content>
        <div class="md-display-1">{{title}}</div>
        <component
          v-bind:is="view"
          :[currentAttribute]="currentValue"
          @updateStatus="handleUpdateStatus"
          :key="componentKey"
          ></component>
      </md-app-content>
    </md-app>
  </div>
</template>

<script>

import AdminOverview from '@/components/AdminOverview.vue'
import AdminOrderList from '@/components/AdminOrderList.vue'
import AdminUserList from '@/components/AdminUserList.vue'

import router from '@/router.js'

export default {
  name: 'adminDashboard',
  data: () => ({
    menuVisible: false,
    view: "home",
    title: "Welcome!",
    currentAttribute: "",
    currentValue: "",
    orders: null,
    users: null,
    user_id: null,
    componentKey:0
  }),
  components: {
    "home": AdminOverview,
    "order-list": AdminOrderList,
    "user-list": AdminUserList,
  },
  methods: {
    toggleMenu() {
      this.menuVisible = !this.menuVisible
    },
    switchView(view) {
      this.view = view;
      this.cleanComponentVar();

      switch (view) {
        case "home":
          this.title = "Welcome!";
          break;

        case "order-list":
          this.title = "All Orders";
          this.currentAttribute = "orders";
          this.currentValue = this.orders.slice();
          break;

        case "user-list":
          this.title = "All Users";
          this.currentAttribute = "users";
          this.currentValue = this.users.slice();
          break;
        default:
          // code block
      }
    },
    cleanComponentVar() {
      this.currentAttribute = "";
      this.currentValue = "";
    },
    getOrderInfo() {
      var self = this;
      var token = localStorage.getItem("admintoken")
      this.axios
        .get(this.$store.state.apiUrl+"/orders",{
          headers: {
            Authorization: "Bearer "+token
          }
        })
        .then(function (response){
          if (response.data.status == "success") {
            self.orders = response.data.orderinfo;
            self.currentValue = self.orders.slice();
            self.componentKey++
          } else {
            router.push('/admin-login')
          }
        })
        .catch(function () {
          router.push('/admin-login')
        });
    },
    getUserInfo() {
      var self = this;
      var token = localStorage.getItem("admintoken")
      this.axios
        .get(this.$store.state.apiUrl+"/users",{
          headers: {
            Authorization: "Bearer "+token
          }
        })
        .then(function (response){
          if (response.data.status == "success") {
            self.users = response.data.userinfo
          } else {
            router.push('/admin-login')
          }
        })
        .catch(function () {
          router.push('/admin-login')
        });
    },
    handleUpdateStatus() {
      this.getOrderInfo()
    },
    logout() {
      localStorage.removeItem("admintoken");
      this.$store.commit('LOGOUT_ADMIN');
      router.push('/admin-login')
    }

  },
  mounted() {
    this.getOrderInfo();
    this.getUserInfo();
  }
}
</script>

<style lang="scss" scoped>
.md-app {
    min-height: 350px;
    border: 1px solid rgba(#000, .12);
}

.md-drawer {
    width: 230px;
    max-width: calc(100vw - 125px);
}

.md-display-1 {
  margin-bottom:40px;
}
</style>
