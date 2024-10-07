<template>
	<JetFormSection @submitted="updateProfileInformation">
		<template #title>
			Profile Information
		</template>

		<template #description>
			Update your account's profile information and email address.
		</template>

		<template #form>
			<!-- Profile Photo -->
			<div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
				<!-- Profile Photo File Input -->
				<input ref="photoInput" type="file" class="hidden" @change="updatePhotoPreview">

				<JetLabel for="photo" value="Photo" />

				<!-- Current Profile Photo -->
				<div v-show="!photoPreview" class="mt-2">
					<img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
				</div>

				<!-- New Profile Photo Preview -->
				<div v-show="photoPreview" class="mt-2">
					<span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
						:style="'background-image: url(\'' + photoPreview + '\');'" />
				</div>

				<JetSecondaryButton class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
					Select A New Photo
				</JetSecondaryButton>

				<JetSecondaryButton v-if="user.profile_photo_path" type="button" class="mt-2"
					@click.prevent="deletePhoto">
					Remove Photo
				</JetSecondaryButton>

				<JetInputError :message="form.errors.photo" class="mt-2" />
			</div>

			<!-- Username -->
			<div class="col-span-6 sm:col-span-4">
				<JetLabel for="username" value="Username" />
				<JetInput id="username" v-model="form.username" type="text" class="mt-1 block w-full"
					autocomplete="username" />
				<JetInputError :message="form.errors.username" class="mt-2" />
			</div>

			<!-- First Name -->
			<div class="col-span-6 sm:col-span-4">
				<JetLabel for="first_name" value="First Name" />
				<JetInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full"
					autocomplete="first_name" />
				<JetInputError :message="form.errors.first_name" class="mt-2" />
			</div>

			<!-- Last Name -->
			<div class="col-span-6 sm:col-span-4">
				<JetLabel for="last_name" value="Last Name" />
				<JetInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full"
					autocomplete="last_name" />
				<JetInputError :message="form.errors.last_name" class="mt-2" />
			</div>

			<!-- Email -->
			<div class="col-span-6 sm:col-span-4">
				<JetLabel for="email" value="Email" />
				<JetInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
				<JetInputError :message="form.errors.email" class="mt-2" />

				<div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null">
					<p class="text-sm mt-2">
						Your email address is unverified.

						<Link :href="route('verification.send')" method="post" as="button"
							class="underline text-gray-600 hover:text-gray-900" @click.prevent="sendEmailVerification">
						Click here to re-send the verification email.
						</Link>
					</p>

					<div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
						A new verification link has been sent to your email address.
					</div>
				</div>
			</div>

			<!-- Public -->
			<div class="col-span-6 sm:col-span-4">
				<JetLabel for="public" value="Public profile?" />
				<JetCheckbox id="public" v-model:checked="form.public" name="public" />
				<span class="ml-2 text-xs text-gray-700">
					Ticking this will mean people can see your collection.
				</span>
				<JetInputError :message="form.errors.public" class="mt-2" />
			</div>
		</template>

		<template #actions>
			<JetActionMessage :on="form.recentlySuccessful" class="mr-3">
				Saved.
			</JetActionMessage>

			<JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Save
			</JetButton>
		</template>
	</JetFormSection>
</template>

<script setup>
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetButton from '@/Jetstream/Button.vue';
import JetCheckbox from '@/Jetstream/Checkbox.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
	user: Object,
});

const form = useForm({
	_method: 'PUT',
	username: props.user.username,
	first_name: props.user.first_name,
	last_name: props.user.last_name,
	email: props.user.email,
	public: props.user.public,
	photo: null,
});

const verificationLinkSent = ref(null);
const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
	if (photoInput.value) {
		form.photo = photoInput.value.files[0];
	}

	form.post(route('user-profile-information.update'), {
		errorBag: 'updateProfileInformation',
		preserveScroll: true,
		onSuccess: () => clearPhotoFileInput(),
	});
};

const sendEmailVerification = () => {
	verificationLinkSent.value = true;
};

const selectNewPhoto = () => {
	photoInput.value.click();
};

const updatePhotoPreview = () => {
	const photo = photoInput.value.files[0];

	if (!photo) return;

	const reader = new FileReader();

	reader.onload = (e) => {
		photoPreview.value = e.target.result;
	};

	reader.readAsDataURL(photo);
};

const deletePhoto = () => {
	router.delete(route('current-user-photo.destroy'), {
		preserveScroll: true,
		onSuccess: () => {
			photoPreview.value = null;
			clearPhotoFileInput();
		},
	});
};

const clearPhotoFileInput = () => {
	if (photoInput.value?.value) {
		photoInput.value.value = null;
	}
};
</script>
