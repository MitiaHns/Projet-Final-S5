import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import Home from '../views/Home.vue';
import Register from '../views/Register.vue';
import Login from '../views/Login.vue';
import AddCar from '../views/AddCar.vue';
import CarDetails from '../views/CarDetails.vue';
import Payment from '../views/Payment.vue';
import { auth } from '../firebase';

const routes: Array<RouteRecordRaw> = [
  {
    path: '/',
    redirect: '/home'
  },
  {
    path: '/home',
    component: Home,
    meta: { requiresAuth: true }
  },
  {
    path: '/register',
    component: Register
  },
  {
    path: '/login',
    component: Login
  },
  {
    path: '/add-car',
    component: AddCar,
    meta: { requiresAuth: true }
  },
  {
    path: '/car/:id',
    component: CarDetails,
    meta: { requiresAuth: true }
  },
  {
    path: '/payment/:id',
    component: Payment,
    meta: { requiresAuth: true }
  }
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
});

router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
  const user = auth.currentUser;

  if (requiresAuth && !user) {
    next('/login');
  } else if ((to.path === '/login' || to.path === '/register') && user) {
    next('/home');
  } else {
    next();
  }
});

export default router;
