<template>
	<DialogModal :show="show" @close="close">
		<template #title>
			Adding "{{ card.name }}" to your collection
		</template>
		<template #content>
			<form>
				<div class="grid grid-cols-1 mt-2">
					<div>
						<JetLabel :required="true">
							Quantity
						</JetLabel>
						<div class="mt-2 grid grid-cols-3 items-center w-1/4">
							<div>
								<MinusIcon @click.prevent="decreaseQuantity"
									class="size-5 hover:text-black hover:cursor-pointer" :class="[
										{
											'text-gray-200': form.quantity == 1,
											'text-gray-500': form.quanity > 1,
										}]" />
							</div>
							<div class="text-lg font-semibold">
								{{ form.quantity }}
							</div>
							<div>
								<PlusIcon @click.prevent="increaseQuantity"
									class="size-5 text-gray-500 hover:text-black hover:cursor-pointer" />
							</div>
						</div>
					</div>
					<div class="mt-5">
						<JetLabel :required="true" class="mb-2">
							Rarity
						</JetLabel>
						<Multiselect v-model="form.rarity_id" :options="rarityOptions" :canDeselect="false"
							:canClear="false" :searchable="true" openDirection="top" />
						<a class="hyperlink text-sm mt-5"
							href="https://totalcards.net/blogs/yugioh/how-to-identify-yu-gi-oh-card-rarities"
							target="_blank">Which rarity do I have?</a>
						<span class="text-xs text-gray-400">
							If link is broken a quick Google search will suffice.
						</span>
					</div>
				</div>
			</form>
		</template>
		<template #footer>
			<div class="flex w-full justify-between">
				<div>
					<JetButton type="button" @click.prevent="close()">
						Close
					</JetButton>
				</div>
				<div>
					<JetButton type="submit" colour="secondary" @click.prevent="submit()">
						Submit
					</JetButton>
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

const emit = defineEmits(['close']);

const close = () => emit('close');

const rarityOptions = computed(() => props.rarities.map((rarity) => {
	return {
		value: rarity.id,
		label: rarity.name,
	}
}));

const decreaseQuantity = () => {
	if (props.form.quantity == 1) return;

	props.form.quantity--;
}

const increaseQuantity = () => props.form.quantity++;

const submit = () => {
	props.form.post(route('card.add-card-to-collection', props.card), {
		onFinish: () => {
			props.form.reset();
			close();
		},
	});
}
</script>
