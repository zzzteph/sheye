import './bootstrap';
import { createApp, h } from 'vue'

import { createInertiaApp } from '@inertiajs/vue3'
import.meta.glob([
  '../images/**',
  '../fonts/**',
]);
import Layout from './Pages/Layout/Main.vue'
import { ZiggyVue } from 'ziggy-js';
import { Ziggy } from './ziggy.js';

createInertiaApp({
     progress: {
    color: '#29d',
  },
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    let page = pages[`./Pages/${name}.vue`]
    page.default.layout = page.default.layout || Layout
    return page
  },
  title: title => title ? `${title}` : 'ShrewdEye',
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue,Ziggy);



    app.mount(el);
  },
})



$(document).ready(function() {


  $(".navbar-burger").click(function() {


      $(".navbar-burger").toggleClass("is-active");
      $(".navbar-menu").toggleClass("is-active");

  });



});


document.addEventListener('inertia:navigate', (event) => {
  $(".navbar-burger").removeClass("is-active");
  $(".navbar-menu").removeClass("is-active");
})