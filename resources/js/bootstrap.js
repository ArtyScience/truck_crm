

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;
axios.defaults.withCredentials = true;

/*TODO: IT SHOULD NOT BE GLOBAL*/
axios.get('/sanctum/csrf-cookie').then(response => {
    window.axios.defaults.headers.common['X-XSRF-TOKEN'] = response.config.headers['X-XSRF-TOKEN'];
});

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

/*
* GLOBAL GOOGLE API REQUEST
* TODO: MAKE api key get from config
* */
// const apiKey = 'AIzaSyAb726TP38q1UpX8-O-hRdeeNobXO4pAcc'
// const script = document.createElement("script");
// script.setAttribute(
//     "src",
//     `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&v=weekly&callback=initMap`
// ), window.initMap = () => {
// }, script.onerror = async (c) => {
// }, document.head.appendChild(script);

const loadGoogleMapsScript = (apiKey) => {
    return new Promise((resolve, reject) => {
        // Check if the Google Maps API is already loaded
        if (typeof window.google === "object" && typeof window.google.maps === "object") {
            resolve(); // Already loaded
            return;
        }
        // Create the script element
        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&v=weekly&callback=initMap`;
        script.async = true;
        script.defer = true;
        // Resolve the promise when the script is loaded
        window.initMap = () => {
            resolve();
        };
        // Reject the promise on error
        script.onerror = () => {
            reject(new Error("Failed to load the Google Maps API script"));
        };

        // Append the script to the document
        document.head.appendChild(script);
    });
};

// Usage example
const apiKey = "AIzaSyAb726TP38q1UpX8-O-hRdeeNobXO4pAcc";

const initGoogleMaps = async () => {
    try {
        await loadGoogleMapsScript(apiKey);
        console.log("Google Maps API loaded successfully");
        // Perform map initialization or other actions here
    } catch (error) {
        console.error("Error loading Google Maps API:", error);
    }
};

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
