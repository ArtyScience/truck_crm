import axios from 'axios';
window.axios = axios;
axios.defaults.withCredentials = true;

const setupRequestJwtToken = () => {
	axios.get('/sanctum/csrf-cookie').then(response => {
		window.axios.defaults.headers.common['X-XSRF-TOKEN']
			= response.config.headers['X-XSRF-TOKEN'];
	});
	window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}
setupRequestJwtToken()

const loadGoogleMap = () => {
	const apiKey = 'AIzaSyAb726TP38q1UpX8-O-hRdeeNobXO4pAcc'
	const script = document.createElement("script");
	script.setAttribute(
		"src",
		`https://maps.googleapis.com/maps/api/js?key=
		${apiKey}&loading=async&libraries=places&v=weekly&callback=initMap`
	), window.initMap = () => {
	}, script.onerror = async (c) => {
	}, document.head.appendChild(script);
}
loadGoogleMap()

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
