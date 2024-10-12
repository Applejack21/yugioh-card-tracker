<template>
	<DialogModal :show="show" @close="close">
		<template #title>
			Multiple rarites to decrease quantity for.
		</template>
		<template #content>
			<div>
				You have collected multiple rarities of this card. Please select the rarity to decrease quantity for.
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
			<div v-for="rarity in rarities"
				class="grid grid-cols-3 w-full bg-gray-200 odd:bg-gray-100 border-b border-r border-l border-black py-5 px-2">
				<div class="col-span-2 my-auto">
					{{ rarity.rarity_name }}
				</div>
				<div class="col-span-1 w-full">
					<div class="mt-2 grid grid-cols-3 items-center">
						<div>
							<MinusIcon @click.prevent="$emit('decrease-quantity', rarity)"
								class="size-5 hover:text-black hover:cursor-pointer" />
						</div>
						<div class="text-lg font-semibold">
							{{ rarity.quantity }}
						</div>
						<div>
							<PlusIcon
								class="size-5 text-gray-300 hover:cursor-pointer" />
						</div>
					</div>
				</div>
			</div>
		</template>
	</DialogModal>
</template>

<script setup>
import JetButton from '@/Jetstream/Button.vue';
import JetLabel from '@/Jetstream/Label.vue';
import DialogModal from "@/Jetstream/DialogModal.vue";
import { MinusIcon, PlusIcon } from "@heroicons/vue/24/outline";
import Multiselect from '@vueform/multiselect';
import { computed } from "vue";

const props = defineProps({
	card: Object,
	form: Object,
	rarities: Object,
	show: Boolean,
});

const emit = defineEmits(['close', 'decrease-clicked']);

const close = () => emit('close');
</script>
