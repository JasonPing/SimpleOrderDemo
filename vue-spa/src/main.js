import Vue from 'vue';
import App from './App.vue';
import router from './router';
import store from './store';
import './registerServiceWorker';

// vue material
import VueMaterial from 'vue-material';
import 'vue-material/dist/vue-material.min.css';
import 'vue-material/dist/theme/default.css';
Vue.use(VueMaterial);

// axios
import axios from 'axios';
import VueAxios from 'vue-axios';
Vue.use(VueAxios, axios);

// vue toaster
import Toasted from 'vue-toasted';
Vue.use(Toasted);

// register error toaster
var errorOption = {
    type: 'error',
    icon: 'error_outline',
    duration: '2000',
  };

Vue.toasted.register('app_error', (msg) => {
        return msg.message;
      }, errorOption);

// register success toaster
var successOption = {
    type: 'success',
    icon: 'check',
    duration: '2000',
  };
Vue.toasted.register('app_success', (msg) => {
        return msg.message;
      }, successOption);

Vue.config.productionTip = false;

new Vue({
  router,
  store,
  render: h => h(App),
}).$mount('#app');
