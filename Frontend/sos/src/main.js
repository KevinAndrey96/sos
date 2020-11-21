// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import App from './App'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { VuejsDatatableFactory } from 'vuejs-datatable';
import VuePaginate from 'vue-paginate'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

Vue.use(VuePaginate)
Vue.use(BootstrapVue)
Vue.use(IconsPlugin)
library.add(fas)
Vue.use( VuejsDatatableFactory);
Vue.component('font-awesome-icon', FontAwesomeIcon)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  el: '#app',
  components: { App },
  template: '<App/>',
})
