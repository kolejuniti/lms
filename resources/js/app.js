/**
 * Load all the project's JavaScript dependencies.
 * This includes Bootstrap, Vue, and other libraries.
 */
require('./bootstrap');

import { createApp } from 'vue';
// import feather from 'feather-icons';

// Create the Vue application instance.
const app = createApp({});

// Register your Vue components.
import ExampleComponent from './components/TextBox/index.vue';
app.component('example-component', ExampleComponent);

// Define a global method to dispatch a custom event.
window.getMessage = function(ic) {
    const event = new CustomEvent('message-requested', { detail: { ic } });
    window.dispatchEvent(event);
};

// Listen for the custom event.
window.addEventListener('message-requested', function(event) {
    // Call your custom function (make sure getNewStudent() is defined)
    getNewStudent();
});

// Mount the Vue app only if the element exists.
if (document.getElementById('app')) {
    app.mount('#app');
}

// // Initialize Feather icons after the DOM is fully loaded.
// document.addEventListener('DOMContentLoaded', () => {
//     feather.replace();
// });

// Import your React setup.
require('./react-app');
