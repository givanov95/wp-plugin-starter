/// <reference types="vite/client" />

declare module "*.vue" {
	import type { DefineComponent } from "vue";
	const component: DefineComponent<{}, {}, any>;
	export default component;
}

import type { WpRestUrlData } from "@givanov95/wp-plugin-core-frontend";

declare global {
	interface Window {
		WpPluginStarter?: WpRestUrlData;
	}
}
