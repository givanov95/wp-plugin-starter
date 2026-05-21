import { createApp } from "vue";
import App from "./App.vue";
import "../css/main.css";

const MOUNT_ID = "wp-plugin-starter-app";

function mount(): void {
	const el = document.getElementById(MOUNT_ID);
	if (!el) {
		return;
	}
	createApp(App).mount(el);
}

if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", mount);
} else {
	mount();
}
