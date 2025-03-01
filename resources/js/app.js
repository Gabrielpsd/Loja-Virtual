import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import VueTheMask from 'vue-the-mask'
import { VMoney } from 'v-money';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import NavBar from '../js/Pages/NavBar/Navbar.vue'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    console.log(name)
    let page = pages[`./Pages/${name}.vue`]

    console.log(page)

    page.default.layout = NavBar

    return page
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(VueTheMask)
      .use(ElementPlus)
      .directive('money', VMoney)
      .mount(el)
  },
})