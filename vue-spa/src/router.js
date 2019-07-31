import Vue from 'vue';
import Router from 'vue-router';
import Home from './views/Home.vue';
import UserLoginPage from './views/UserLoginPage.vue';
import AdminLoginPage from './views/AdminLoginPage.vue';
import OrderDashboardPage from './views/OrderDashboardPage.vue';
import AdminDashboardPage from './views/AdminDashboardPage.vue';

Vue.use(Router);

import store from './store';

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/user-login',
      name: 'userLogin',
      component: UserLoginPage,
    },
    {
      path: '/dashboard/:id',
      name: 'orderDashboard',
      component: OrderDashboardPage,
      meta: { requiresUserLogin: true },
    },
    {
      path: '/admin-login',
      name: 'adminLogin',
      component: AdminLoginPage,
    },
    {
      path: '/admin-dashboard',
      name: 'adminDashboardPage',
      component: AdminDashboardPage,
      meta: { requiresAdminLogin: true },
    },
    {
      path: '*',
      redirect: '/',
    },
  ],
});

//guard clause
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresUserLogin)
      && store.state.isUserLogged == false) {
      next('/user-login');
    } else if (to.matched.some(record => record.meta.requiresAdminLogin)
      && store.state.isAdminLogged == false) {
      next('/admin-login');
    } else {
      next();
    }
  });

export default router;
