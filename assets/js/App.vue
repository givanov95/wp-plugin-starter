<script setup lang="ts">
import { onMounted, reactive, ref } from "vue";
import { NotificationManager, RestApiError } from "@givanov95/wp-plugin-core-frontend";
import { exampleApi, type Example, type ExampleInput } from "./api/ExampleApi";

const toasts = NotificationManager.getInstance({
	namespace: "wp-plugin-starter",
	position: "top-right",
});

const items   = ref<Example[]>([]);
const total   = ref(0);
const loading = ref(false);

const form = reactive<ExampleInput>({
	title: "",
	email: "",
	status: "active",
});

async function load(): Promise<void> {
	loading.value = true;
	try {
		const res = await exampleApi.list({ page: 1, per_page: 20 });
		items.value = res.data.items;
		total.value = res.data.total;
	} catch (e) {
		toasts.error(extractMessage(e));
	} finally {
		loading.value = false;
	}
}

async function submit(): Promise<void> {
	if (!form.title || !form.email) {
		toasts.warning("Title and email are required");
		return;
	}

	try {
		await exampleApi.create({ ...form });
		toasts.success("Created");
		form.title = "";
		form.email = "";
		await load();
	} catch (e) {
		toasts.error(extractMessage(e));
	}
}

async function remove(id: number): Promise<void> {
	if (!confirm("Delete this item?")) return;

	try {
		await exampleApi.delete(id);
		toasts.success("Deleted");
		await load();
	} catch (e) {
		toasts.error(extractMessage(e));
	}
}

function extractMessage(e: unknown): string {
	if (e instanceof RestApiError) return e.message;
	if (e instanceof Error) return e.message;
	return "Unknown error";
}

onMounted(load);
</script>

<template>
	<div class="wpps">
		<form class="wpps-form" @submit.prevent="submit">
			<input v-model="form.title" placeholder="Title" />
			<input v-model="form.email" placeholder="Email" type="email" />
			<select v-model="form.status">
				<option value="active">Active</option>
				<option value="inactive">Inactive</option>
			</select>
			<button type="submit">Add</button>
		</form>

		<p v-if="loading">Loading…</p>
		<p v-else-if="!items.length">No items yet. Add one above.</p>

		<table v-else class="wpps-table widefat striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Title</th>
					<th>Email</th>
					<th>Status</th>
					<th>Created</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="item in items" :key="item.id">
					<td>{{ item.id }}</td>
					<td>{{ item.title }}</td>
					<td>{{ item.email }}</td>
					<td>{{ item.status }}</td>
					<td>{{ item.created_at }}</td>
					<td>
						<button type="button" @click="remove(item.id)">Delete</button>
					</td>
				</tr>
			</tbody>
		</table>

		<p class="wpps-total">Total: {{ total }}</p>
	</div>
</template>

<style scoped>
.wpps {
	margin-top: 20px;
	max-width: 960px;
}
.wpps-form {
	display: flex;
	gap: 8px;
	margin-bottom: 20px;
}
.wpps-form input,
.wpps-form select {
	padding: 6px 10px;
}
.wpps-table {
	width: 100%;
	border-collapse: collapse;
}
.wpps-table th,
.wpps-table td {
	padding: 8px 12px;
	text-align: left;
}
.wpps-total {
	margin-top: 12px;
	color: #555;
	font-size: 13px;
}
</style>
