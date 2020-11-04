import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify';
import Header from './components/Header.vue'
import body from './components/body.vue'
import ModalLogin from './components/Regester.vue'
Vue.config.productionTip = false
Vue.component('app-header',Header);
Vue.component('app-body',body);
Vue.component('app-login',ModalLogin);
new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
