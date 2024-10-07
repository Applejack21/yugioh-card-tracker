<template>
	<AuthLayout title="Log in">
		<div class="flex flex-col items-center text-center mb-16 space-y-1">
			<h3 class="mt-[24px] text-gray-700 text-2xl">
				Welcome back.
			</h3>
			<h4 class="text-gray-500 text-xl">
				Lets get your collection upgraded!
			</h4>
		</div>

		<JetValidationErrors class="mb-4" />

		<div v-if="status" class="mb-4 font-medium text-sm text-validation-green">
			{{ status }}
		</div>

		<form @submit.prevent="submit">
			<div>
				<JetLabel for="email" value="Email" />
				<JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required autofocus />
			</div>

			<div class="mt-4">
				<JetLabel for="password" value="Password" />
				<JetInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
					autocomplete="current-password" />
			</div>

			<div class="block mt-4">
				<label class="flex items-center">
					<JetCheckbox v-model:checked="form.remember" name="remember" />
					<span class="ml-2 text-sm text-gray-600">Remember me</span>
				</label>
			</div>

			<div class="flex items-center justify-end mt-4">
				<Link v-if="canResetPassword" :href="route('password.request')"
					class="underline text-sm text-gray-600 hover:text-gray-900">
				Forgot your password?
				</Link>

				<JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
					Log in
				</JetButton>
			</div>
		</form>

		<div class="flex mt-[57px] items-center justify-center">
			<p class="flex">
				No account?
				<span class="ml-2 underline">
					<Link :href="route('register')">
					Sign Up Now
					</Link>
				</span>
			</p>
		</div>
	</AuthLayout>
</template>

<script setup>
import JetButton from '@/Jetstream/Button.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
	canResetPassword: Boolean,
	status: String,
});

const form = useForm({
	email: '',
	password: '',
	remember: false,
});

const submit = () => {
	form.transform(data => ({
		...data,
		remember: form.remember ? 'on' : '',
	})).post(route('login'), {
		onFinish: () => form.reset('password'),
	});
};
</script>
