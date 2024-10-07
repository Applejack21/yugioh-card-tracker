<template>
	<DialogModal :show="show" @close="close" maxWidth="5xl">
		<template #title>
			Your collection for "{{ card.name }}"
		</template>
		<template #content>
			<div>
				<span class="text-sm">
					A list of rarities you've collected for this card.
				</span>
			</div>
			<div
				class="mt-5 grid grid-cols-3 w-full bg-gray-50 border-b border-t border-r border-l border-black py-2 px-2">
				<div class="col-span-2">
					Rarity Name
				</div>
				<div class="col-span-1">
					Quantity
				</div>
			</div>
			<div v-for="rarity in collectedRarities"
				class="grid grid-cols-3 w-full bg-gray-200 odd:bg-gray-100 border-b border-r border-l border-black py-5 px-2">
				<div class="col-span-2 my-auto">
					{{ rarity.rarity_name }}
				</div>
				<div class="col-span-1 p-2 mr-auto my-auto rounded-md text-white bg-[#1d9e74]">
					{{ rarity.quantity }}
				</div>
			</div>
		</template>
	</DialogModal>
</template>

<script setup>
import DialogModal from "@/Jetstream/DialogModal.vue";

const props = defineProps({
	card: Object,
	collectedRarities: Object,
	show: Boolean,
});

const emit = defineEmits(['close']);

const close = () => emit('close');
</script>
