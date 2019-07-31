import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
  state: {
    isUserLogged: !!localStorage.getItem('token'),
    isAdminLogged: !!localStorage.getItem('admintoken'),
    apiUrl: 'http://kobe.test/api',
  },
  mutations: {
    LOGIN_USER(state) {
      state.isUserLogged = true;
    },

    LOGOUT_USER(state) {
      state.isUserLogged = false;
    },

    LOGIN_ADMIN(state) {
      state.isAdminLogged = true;
    },

    LOGOUT_ADMIN(state) {
      state.isAdminLogged = false;
    },
  },
  actions: {

  },
});
