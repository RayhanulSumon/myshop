import Vue from "vue";

require('./bootstrap');

// import vue
window.Vue = require('vue');
import product from "./components/Product";
import objectToFormData from "./objectToFormData";
import components0 from "./components/SellReport";
import vue from 'vue';
import components01 from "./components/EditProfile";
import Gate from './Gate';
import { Form, HasError, AlertError} from 'vform';
import VueProgressBar from 'vue-progressbar';
import swal from 'sweetalert2'
import VueRouter from 'vue-router'

Vue.prototype.$gate = new Gate(window.user);


window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)

//import vue-progressbar

Vue.use(VueProgressBar,options)
const options = {
    color: '#bffaf3',
    failedColor: '#874b4b',
    thickness: '5px',
    transition: {
        speed: '0.2s',
        opacity: '0.6s',
        termination: 300
    },
    autoRevert: true,
    location: 'left',
    inverse: false
}
// import sweetalert2

window.swal= swal;
//config sweetalert2
const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
})
window.toast= toast;

// import vue router

Vue.use(VueRouter)
let routes = [
    {path: '/', component: product},
    {path: '/products', component: product},
    {path: '/sell-report', component: components0},
    {path: '/edit-profile', component: components01},

]
// vue router config
const router = new VueRouter({
    mode: 'history',
    routes
})
Vue.component('pagination', require('./components/include/Pagination.vue').default);
Vue.component('login', require('./components/Login.vue').default);


// file/image external js


window.objectToFormData = objectToFormData;
//filter data
Vue.filter('upText',function (text) {
    if (!text) return ''
    text = text.toString()
    return text.charAt(0).toUpperCase() + text.slice(1)

});

// global vue window
window.Fire = new Vue()

const app = new Vue({
    el: '#app',
    router  //config router
});
