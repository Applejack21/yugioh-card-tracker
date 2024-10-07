<template>
	<DialogModal :show="show" @close="close" maxWidth="5xl">
		<template #title>
			Rarities for "{{ card.name }}"
		</template>
		<template #content>
			<div>
				<span class="text-sm">
					A list of card rarities available to collect.
				</span>
			</div>
			<div
				class="mt-5 grid grid-cols-3 w-full bg-gray-50 border-b border-t border-r border-l border-black py-2 px-2">
				<div class="col-span-2">
					Rarity Name
				</div>
				<div class="col-span-1">
					Code
				</div>
			</div>
			<div v-for="rarity in cardRarities"
				class="grid grid-cols-3 w-full bg-gray-200 odd:bg-gray-100 border-b border-r border-l border-black py-5 px-2">
				<div class="col-span-2 my-auto">
					{{ rarity.name }}
				</div>
				<div class="col-span-1 mr-auto my-auto rounded-md">
					{{ rarity.code !== '' ? rarity.code : '(N/A)' }}
				</div>
			</div>
		</template>
	</DialogModal>
</template>

<script setup>
import DialogModal from "@/Jetstream/DialogModal.vue";
import { inject } from 'vue';

const props = defineProps({
	card: Object,
	cardRarities: Object,
	show: Boolean,
});

const user = inject('user', null);

const emit = defineEmits(['close']);

const close = () => emit('close');
</script>
