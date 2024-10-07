<template>
	<AuthLayout title="Register">
		<div class="flex flex-col items-center text-center mb-16 space-y-1">
			<h3 class="mt-[24px] text-gray-700 text-2xl">
				Time for you to digitalise your collection!
			</h3>
			<h4 class="text-gray-500 text-xl">
				Create an account below.
			</h4>
		</div>

		<JetValidationErrors class="mb-4" />

		<div v-if="status" class="mb-4 font-medium text-sm text-validation-green">
			{{ status }}
		</div>

		<form @submit.prevent="submit">
			<div>
				<JetLabel for="username" value="Username" required />
				<JetInput id="username" v-model="form.username" type="text" class="mt-1 block w-full" required
					autocomplete="username" />
			</div>

			<div class="mt-4">
				<JetLabel for="first_name" value="First Name" />
				<JetInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full"
					autocomplete="first_name" />
			</div>

			<div class="mt-4">
				<JetLabel for="last_name" value="Last Name" />
				<JetInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full"
					autocomplete="last_name" />
			</div>

			<div class="mt-4">
				<JetLabel for="email" value="Email" required />
				<JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" required />
			</div>

			<div class="mt-4">
				<JetLabel for="password" value="Password" required />
				<JetInput id="password" v-model="form.password" type="password" class="mt-1 block w-full" required
					autocomplete="new-password" />
			</div>

			<div class="mt-4">
				<JetLabel for="password_confirmation" value="Confirm Password" required />
				<JetInput id="password_confirmation" v-model="form.password_confirmation" type="password"
					class="mt-1 block w-full" required autocomplete="new-password" />
			</div>

			<div class="mt-4">
				<JetLabel for="public" value="Public profile?" />
				<JetCheckbox id="public" v-model:checked="form.public" name="public" />
				<span class="ml-2 text-xs text-gray-700">
					Ticking this will mean people can see your collection.
				</span>
			</div>

			<div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="mt-4">
				<JetLabel for="terms">
					<div class="flex items-center">
						<JetCheckbox id="terms" v-model:checked="form.terms" name="terms" />

						<div class="ml-2">
							I agree to the <a target="_blank" :href="route('terms.show')"
								class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a>
							and <a target="_blank" :href="route('policy.show')"
								class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
						</div>
					</div>
				</JetLabel>
			</div>

			<div class="flex items-center justify-end mt-4">
				<Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
				Already registered?
				</Link>

				<JetButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
					Register
				</JetButton>
			</div>
		</form>
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

const form = useForm({
	username: '',
	first_name: '',
	last_name: '',
	email: '',
	password: '',
	password_confirmation: '',
	terms: false,
	public: false,
});

const submit = () => {
	form.post(route('register'), {
		onFinish: () => form.reset('password', 'password_confirmation'),
	});
};
</script>
