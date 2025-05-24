import './bootstrap';
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
import vClickOutside from "click-outside-vue3"
import FloatingVue from 'floating-vue'
import 'vue3-toastify/dist/index.css';

import { createApp } from "vue";

const app = createApp({});

const apiKey = 'AIzaSyAb726TP38q1UpX8-O-hRdeeNobXO4pAcc'
const script = document.createElement("script");
script.setAttribute(
    "src",
    `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&v=weekly&callback=initMap`
), window.initMap = () => {
}, script.onerror = async (c) => {
}, document.head.appendChild(script);

/*COMPONENTS*/
import Settings from "./components/common/Settings.vue";
import Table from "./components/core/Table.vue";
import Balance from "./components/common/Balance.vue";
import GoTop from "./components/buttons/GoTop.vue";
import Alert from "./components/common/Alert.vue";
import Rating from "./components/common/Rating.vue";
import Preloader from "./components/common/Preloader.vue";
/*PAGES*/
import PageLayout from "./pages/PageLayout.vue";
import Dashboard from "./pages/Dashboard.vue";
import User from "./pages/User.vue";
import Components from "./pages/Components.vue";
import Leads from "./pages/Leads.vue";
import Deals from "./pages/Deals.vue";
import Tasks from "./pages/Tasks.vue";
import Campains from "./pages/Campains.vue";

/*Global*/
app.use(VueSweetalert2);
app.use(vClickOutside);
app.use(FloatingVue)

/*TODO: use Pages Dynamically*/
/*Components*/
app.component("Settings", Settings);
app.component("v-table", Table);
app.component("v-balance", Balance);
app.component("v-gotop", GoTop);
app.component("v-my-allert", Alert);
app.component("v-ratting", Rating);
app.component("v-preloader", Preloader);
/*PAGES*/
app.component("v-page-layout", PageLayout);
app.component("v-page-dashboard", Dashboard);
app.component("v-page-user", User);
app.component("v-page-components", Components);
app.component("v-page-leads", Leads);
app.component("v-page-deals", Deals);
app.component("v-page-tasks", Tasks);
app.component("v-page-campains", Campains);

const mountedApp = app.mount("#dynamic-content");

/*INFO: Registered new PUSHER worker for notification*/
// if ('serviceWorker' in navigator) {
// 	navigator.serviceWorker.register('service-worker.js')
// 		.then(registration => {
// 			console.log('Service Worker registered with scope:', registration.scope);
//
// 			console.log('navigator.serviceWorker', navigator.serviceWorker);
// 		})
// 		.catch(error => {
// 			console.error('Service Worker registration failed:', error);
// 		});
// }

if ('serviceWorker' in navigator && 'PushManager' in window) {
	navigator.serviceWorker.register('/service-worker.js')
		.then(registration => {
			console.log('Service Worker registered with scope:', registration.scope);

			// Ask for permission
			return Notification.requestPermission();
		})
		.then(permission => {
			if (permission === "granted") {
				console.log("User granted push notifications.");
				// Proceed with push notification setup
			} else if (permission === "denied") {
				console.warn("User denied push notifications.");
			} else {
				console.log("Push notifications permission dismissed.");
			}
		})
		.catch(error => {
			console.error('Service Worker registration or permission request failed:', error);
		});
} else {
	console.warn("Push notifications are not supported in this browser.");
}

/*TODO: Only if user is auth run code below */

// document.getElementById("notifyButton").addEventListener("click", function () {
// 	if (Notification.permission === "granted") {
// 		new Notification("Hello! Notifications are enabled.");
// 		const beamsClient = new PusherPushNotifications.Client({
// 			instanceId: 'ef60a524-6a00-473a-829f-09beb7500203',
// 		});
//
// 		beamsClient.start()
// 			.then(() => beamsClient.addDeviceInterest('calendar_notification'))
// 			.then(() => console.log('Successfully registered and subscribed!'))
// 			.catch(console.error);
//
// 	} else if (Notification.permission !== "denied") {
// 		Notification.requestPermission().then(permission => {
// 			if (permission === "granted") {
// 				new Notification("Thank you for enabling notifications!");
// 			}
// 		});
// 	}
// });

