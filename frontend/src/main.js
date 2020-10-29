import Vue from 'vue'
import App from './App.vue'
import router from './router'
import vuetify from './plugins/vuetify';
import Header from './components/Header.vue'
import body from './components/body.vue'
Vue.config.productionTip = false
Vue.component('app-header',Header);
Vue.component('app-body',body);
new Vue({
  router,
  vuetify,
  render: h => h(App)
}).$mount('#app')
