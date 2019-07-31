<template>
<div class="orderDashboard">
  <md-app>
    <md-app-toolbar class="md-primary" md-elevation="0">
      <md-button class="md-icon-button" @click="toggleMenu" v-if="!menuVisible">
        <md-icon>menu</md-icon>
      </md-button>
      <span class="md-title">My Order</span>
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
          <span class="md-list-item-text">Track Orders</span>
        </md-list-item>

        <md-list-item @click="switchView('order-form')">
          <md-icon>playlist_add</md-icon>
          <span class="md-list-item-text">New Order</span>
        </md-list-item>

        <md-list-item @click="logout()">
          <md-icon>link_off</md-icon>
          <span class="md-list-item-text">Logout</span>
        </md-list-item>

      </md-list>
      </md-tabs>
    </md-app-drawer>

    <md-app-content>
      <div class="md-display-1">{{title}}</div>
      <component
        v-bind:is="view"
        :[currentAttribute]="currentValue"
        :key = "componentKey"
        ></component>
    </md-app-content>
  </md-app>
</div>
</template>

<script>
import OrderList from '@/components/OrderList.vue'
import OrderForm from '@/components/OrderForm.vue'
import OrderOverview from '@/components/OrderOverview.vue'
import router from '@/router.js'

export default {
  name: 'orderDashboard',
  data: () => ({
    menuVisible: false,
    view: "home",
    title: "Welcome!",
    currentAttribute: "",
    currentValue: "",
    orders: null,
    user_id: null,
    componentKey:0,
  }),
  components: {
    "home": OrderOverview,
    "order-list": OrderList,
    "order-form": OrderForm,
  },
  created() {
    this.user_id = this.$route.params.id;
    // make sure user cannot view other user dashboard
    this.checkCurrentUser();
    this.getOrderInfo();
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
          this.getOrderInfo();
          this.title = "Track Orders";
          this.currentAttribute = "orders";

          this.currentValue = this.orders;
          break;
        case "order-form":
          this.title = "New Order";
          this.currentAttribute = "title";
          this.currentValue = "New Order";
          break;
        default:
          // code block
      }
    },
    cleanComponentVar() {
      this.currentAttribute = "";
      this.currentValue = "";
    },
    checkCurrentUser() {
      var token = localStorage.getItem("token")
      this.axios
        .post(this.$store.state.apiUrl+"/checkapikey/",{
          user_id: this.user_id,
          api_key: token,
        },{
          headers: {
            Authorization: "Bearer "+token
          }
        })
        .then(function (response){
          if (response.data.status == "fail") {
            router.push('/user-login')
          }
        })
        .catch(function () {
          router.push('/user-login')
        });
    },
    getOrderInfo() {
      var self = this;
      var token = localStorage.getItem("token")
      this.axios
        .get(this.$store.state.apiUrl+"/orders-users/"+this.user_id,{
          headers: {
            Authorization: "Bearer "+token
          }
        })
        .then(function (response){
          if (response.data.status == "success") {
            self.orders = response.data.orderinfo
            self.currentValue = self.orders.slice()
            self.componentKey++
          } else {
            self.orders = [];
          }
        })
        .catch(function () {
          router.push('/user-login')
        });
    },
    logout() {
      localStorage.removeItem("token");
      this.$store.commit('LOGOUT_USER');
      router.push('/user-login')
    }
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
