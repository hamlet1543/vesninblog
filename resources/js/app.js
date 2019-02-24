
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');

import { library } from '@fortawesome/fontawesome-svg-core'
import { faCheck, faPlus, faFolderPlus, faPlusCircle, faPlusSquare, faEdit, faPencilAlt, faTimes, faTimesCircle } from '@fortawesome/free-solid-svg-icons'

library.add(faCheck, faPlus, faFolderPlus, faPlusCircle, faPlusSquare, faEdit, faPencilAlt, faTimes, faTimesCircle)


import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
Vue.component('font-awesome-icon', FontAwesomeIcon);


Vue.component('task-card', require('./components/TaskCard.vue'));
Vue.component('download-component', require('./components/DownloadComponent.vue'));
Vue.component('add-card', require('./components/AddCard.vue'));
Vue.component('nav-component', require('./components/NavComponent.vue'));
Vue.component('folder-tasks', require('./components/FolderTasks.vue'));
Vue.component('list-cards', require('./components/ListCards.vue'));
Vue.component('slider', require('./components/SliderComponent.vue'));
Vue.component('load-image', require('./components/LoadImage.vue'));

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key)))

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.config.productionTip = false
const app = new Vue({
    el: '#app',
    data: {
    	reg: false
    }
});
