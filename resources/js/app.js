/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { createApp } from 'vue';
const app = createApp({});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import ExampleComponent from './components/TextBox/index.vue';
app.component('example-component', ExampleComponent);


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Define a global method to dispatch a custom event
window.getMessage = function(ic) {
    const event = new CustomEvent('message-requested', { detail: { ic } });
    window.dispatchEvent(event);
};

// Set up the listener for the custom event
window.addEventListener('message-requested', function(event) {
    getNewStudent();
});

app.mount('#app');

// Import React setup
require('./react-app');



