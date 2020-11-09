import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/post',
    component: () => import('@/views/PostDetail'),
    hidden: true
  },
  {
    path: '*',
    component: () => import('@/views/NotFound'),
    hidden: true
  },
  
]

const router = new VueRouter({
  mode: 'history',
  routes
});

export default router
