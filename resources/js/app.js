import { createApp } from 'vue';
import axios from 'axios';

import CreateWichtelGroupForm from './components/CreateWichtelGroupForm.vue';
import WichtelgroupView from './components/WichtelgroupView.vue';
import MemberAddForm from './components/MemberAddForm.vue';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({});

app.component('create-wichtelgroup', CreateWichtelGroupForm);
app.component('wichtelgroup-view', WichtelgroupView);
app.component('member-add-form', MemberAddForm);

app.mount('#app');
