import { RestApi } from "@givanov95/wp-plugin-core-frontend";

export interface Example {
	id: number;
	title: string;
	email: string;
	status: string;
	created_at: string;
}

export interface PaginatedExamples {
	items: Example[];
	total: number;
	page: number;
	per_page: number;
}

export interface ExampleInput {
	title: string;
	email: string;
	status?: string;
}

export class ExampleApi extends RestApi {
	list(params: { page?: number; per_page?: number; status?: string } = {}) {
		const search = new URLSearchParams();
		if (params.page)     search.set("page", String(params.page));
		if (params.per_page) search.set("per_page", String(params.per_page));
		if (params.status)   search.set("status", params.status);

		const query = search.toString();
		const route = `wp-plugin-starter/v1/examples${query ? `?${query}` : ""}`;

		return this.restFetch<{ success: true; data: PaginatedExamples }>(
			route,
			"GET",
		);
	}

	create(data: ExampleInput) {
		return this.restFetch<{ success: true; data: Example }>(
			"wp-plugin-starter/v1/examples",
			"POST",
			data,
		);
	}

	delete(id: number) {
		return this.restFetch<{ success: true; data: { deleted: number } }>(
			`wp-plugin-starter/v1/examples/${id}`,
			"DELETE",
		);
	}
}

export const exampleApi = new ExampleApi({
	windowPropertyName: "WpPluginStarter",
	options: {
		logErrors: true,
		debug: import.meta.env.DEV,
	},
});
