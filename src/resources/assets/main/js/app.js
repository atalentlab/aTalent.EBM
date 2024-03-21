
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue');
window.QRCode = require('qrcode');

//window.axios = require('axios');
require('parsleyjs');
require('slick-carousel');
require('./vendor/lightbox');

import vuescroll from 'vue-scroll'

Vue.use(vuescroll,{debounce: 600})
Vue.component('rank-table', require('./vue/components/RankTable.vue').default);

const app = new Vue({
    el: '#app'
});


import * as animeCustom from './components/anime-custom';
import * as slider from './components/slider';
import * as bgMover from './components/bg-hover';
import * as scrollAnimate from './components/scroll-animate';
import * as remoteForm from './components/remote-form';
import * as lightBox from './components/lightbox';
import * as custom from './components/custom';

$(function() {
   animeCustom.init();
   scrollAnimate.init();
   slider.init();
   bgMover.init();
   remoteForm.init();
   lightBox.init();
   custom.init();
});
