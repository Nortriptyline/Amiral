require('./bootstrap');

import Vue from 'vue';

import { InertiaApp } from '@inertiajs/inertia-vue';
import { InertiaForm } from 'laravel-jetstream';
import PortalVue from 'portal-vue';

import VueTippy from 'vue-tippy'

import simplebar from 'simplebar-vue';
import 'simplebar/dist/simplebar.min.css';

import Slugify from '@/Mixins/Slugify';

import 'animate.css';

Vue.mixin({ methods: { route } });
Vue.mixin(Slugify);
Vue.use(InertiaApp);
Vue.use(InertiaForm);
Vue.use(PortalVue);
Vue.use(VueTippy);

Vue.component('simplebar', simplebar);

const app = document.getElementById('app');

new Vue({
    render: (h) =>
        h(InertiaApp, {
            props: {
                initialPage: JSON.parse(app.dataset.page),
                resolveComponent: (name) => require(`./Pages/${name}`).default,
            },
        }),
}).$mount(app);
