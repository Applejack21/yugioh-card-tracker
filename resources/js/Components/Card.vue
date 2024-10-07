<template>
	<Link :href="'#'">
	<!-- Image and overlayed info -->
	<div class="relative">
		<div class="flex items-center transition-transform ease-in-out hover:scale-[1.1] hover:opacity-100 hover:transition-opacity hover:duration-500"
			:class="[
				{ 'opacity-100': quantities.total > 0 },
				{ 'opacity-50': quantities.total == 0 },
			]">
			<img :src="card.image" :alt="`${card.name} card image`" class="mx-auto py-2 text-center rounded-[20p]"
				width="250" height="400">

			<div
				class="absolute z-10 bottom-0 w-full justify-center space-x-16 xl:space-x-0 lg:left-0 lg:right-0 flex xl:justify-between pb-2">
				<div class=" bg-white px-2 truncate text-[12px] rounded-tr-md">
					<template v-if="latestSet">
						{{ latestSet.code }}
					</template>
					<template v-else>
						N/A
					</template>
				</div>

				<div class="bg-white px-2 max-w-[100px] truncate text-[12px] rounded-tl-md" :title="card.type">
					{{ card.type }}
				</div>
			</div>
		</div>
	</div>
	</Link>

	<!-- Type/favourite -->
	<div class="mt-5 flex justify-between px-1 grid-cols-3 mx-auto items-center w-full">
		<div>
			<img :src="card.type_image" class="size-7" :title="typeTitle" />
		</div>
		<div class="text-sm text-gray-500">
			{{ card.race }}
		</div>
		<div>
			<HeartIcon class="size-5 fill-gray-400 hover:fill-red-300 hover:cursor-pointer" />
		</div>
	</div>

	<!-- Collection stats -->
	<div class="border-t-2 pt-2 mt-2">
		<div class="flex justify-between px-0.5 grid-cols-3 mx-auto items-center w-full">
			<div class="flex items-center flex-shrink-0 size-8 justify-center leading-none hover:cursor-pointer"
				title="View Your Collection" @click.prevent="displayUserCollection">
				<div>
					<span class="card card-squares card-first-set z-30 relative" :class="[
						{ 'active': quantities.total > 0 }
					]" />
				</div>
			</div>
			<!-- Add/remove buttons and total -->
			<div class="flex gap-x-5 justify-center items-center w-full" v-if="cardRarities.length && user">
				<div>
					<MinusIcon class="size-5 text-gray-500 hover:text-black hover:cursor-pointer" />
				</div>
				<div class="text-lg font-semibold">
					{{ quantities.total }}
				</div>
				<Menu as="div">
					<div class="flex items-center">
						<MenuButton as="template" v-slot="{ open }">
							<slot name="button" :open="open">
								<button>
									<PlusIcon
										class="size-5 text-gray-500 hover:text-black hover:cursor-pointer transition duration-300 ease-in-out"
										:class="[{ 'rotate-45': open }]" />
								</button>
							</slot>
						</MenuButton>
					</div>

					<transition enter-active-class="transition duration-100 ease-out"
						enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
						leave-active-class="transition duration-75 ease-in"
						leave-from-class="transform scale-100 opacity-100"
						leave-to-class="transform scale-95 opacity-0">
						<MenuItems
							class="max-h-[200px] overflow-y-scroll absolute z-40 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
							<div class="p-1">
								<MenuItem as="div" v-for="rarity in cardRarities" v-slot="{ active }">
								<button @click.prevent="addRarityToCollection(rarity)" :title="rarity.name" :class="[
									active ? 'bg-gray-200' : 'text-gray-900',
									'group grid grid-cols-5 w-full items-center rounded-md p-2 text-sm disabled:opacity-30 text-left',
								]">
									<PlusIcon class="size-5 mr-2" :active="active" />
									<span class="truncate col-span-3">
										{{ rarity.name }}
									</span>
									<div class="ml-auto p-2 rounded-md text-white" :class="[
										{'bg-gray-500': findQuantityByRarityName(rarity.name) <= 0},
										{'bg-[#1d9e74]': findQuantityByRarityName(rarity.name) > 0},
									]">
										{{ findQuantityByRarityName(rarity.name) }}
									</div>
								</button>
								</MenuItem>
							</div>
						</MenuItems>
					</transition>
				</Menu>
			</div>
			<div v-else class="text-center mx-5 w-full text-xs text-red-600">
				<span v-if="!user">
					Log in to add this card to your collection.
				</span>
				<span v-else>
					No card rarities for this card yet.
				</span>
			</div>
			<Menu as="div" class="relative ml-auto">
				<div class="flex items-center">
					<MenuButton as="template" v-slot="{ open }">
						<slot name="button" :open="open">
							<button
								:class="['cursor-pointer rounded-sm text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary']">
								<EllipsisVerticalIcon :class="['size-6 transition-transform', { 'rotate-90': open }]" />
							</button>
						</slot>
					</MenuButton>
				</div>

				<transition enter-active-class="transition duration-100 ease-out"
					enter-from-class="transform scale-95 opacity-0" enter-to-class="transform scale-100 opacity-100"
					leave-active-class="transition duration-75 ease-in"
					leave-from-class="transform scale-100 opacity-100" leave-to-class="transform scale-95 opacity-0">
					<MenuItems
						class="absolute z-40 top-5 right-0 mt-2 w-56 origin-top-right divide-y divide-gray-100 rounded-md bg-white shadow-lg ring-1 ring-black/5 focus:outline-none">
						<div class="p-1">
							<MenuItem v-slot="{ active }" @click.prevent="displayPrices">
							<button :class="[
								active ? 'bg-gray-200' : 'text-gray-900',
								'group flex w-full items-center rounded-md p-2 text-sm disabled:opacity-30',
							]" :disabled="!cardPrices">
								<CurrencyPoundIcon class="size-5 mr-2" :active="active" />
								View Prices
							</button>
							</MenuItem>
							<MenuItem v-slot="{ active }" @click.prevent="displayRarities">
							<button :class="[
								active ? 'bg-gray-200' : 'text-gray-900',
								'group flex w-full items-center rounded-md p-2 text-sm disabled:opacity-30',
							]" :disabled="!cardRarities.length">
								<SwatchIcon class="size-5 mr-2" :active="active" />
								View Card Rarities
							</button>
							</MenuItem>
						</div>
					</MenuItems>
				</transition>
			</Menu>
		</div>
	</div>

	<PriceModal :card="card" :cardPrices="cardPrices" :show="showPrices" @close="closePrices" />

	<RarityModal :card="card" :cardRarities="cardRarities" :show="showRarities" @close="closeRarities" />

	<UserCardCollectionModal :card="card" :collectedRarities="quantities.rarities" :show="showUserCollection"
		@close="closeUserCollection" />

	<AddCardToCollectionModal :card="card" :form="addRarityForm" :rarities="cardRarities" :show="showAddModal"
		@close="closeAddModal" />
</template>

<script setup>
import AddCardToCollectionModal from "@/Components/AddCardToCollectionModal.vue";
import PriceModal from "@/Components/PriceModal.vue";
import RarityModal from "@/Components/RarityModal.vue";
import UserCardCollectionModal from "@/Components/UserCardCollectionModal.vue";
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { CurrencyPoundIcon, SwatchIcon } from '@heroicons/vue/24/outline';
import { EllipsisVerticalIcon, HeartIcon, MinusIcon, PlusIcon } from '@heroicons/vue/24/solid';
import { useForm } from "@inertiajs/vue3";
import { computed, inject, ref } from 'vue';

const props = defineProps({
	card: Object,
});

const user = inject('user', null);
const quantities = computed(() => props.card.quantities);
const cardSets = computed(() => props.card.card_sets);
const cardPrices = computed(() => props.card.card_prices);
const cardRarities = computed(() => {
	const rarities = [];

	cardSets.value.forEach(set => {
		if (set.card_rarity) {
			// Only add if the rarity id doesn't already exist.
			if (!rarities.find((rarity) => rarity.id == set.card_rarity.id)) {
				rarities.push(set.card_rarity);
			}
		}
	});

	rarities.sort((a, b) => a.name.localeCompare(b.name))

	return rarities;
});

const latestSet = computed(() => {
	if (!cardSets.value.length) {
		return null;
	}

	// Sort the card_sets array by release_date in descending order
	const latestCardSet = props.card.card_sets.reduce((latest, current) => {
		const latestDate = new Date(latest.card_set.release_date);
		const currentDate = new Date(current.card_set.release_date);
		return currentDate > latestDate ? current : latest;
	});

	return latestCardSet;
});

const typeTitle = computed(() => {
	const type = props.card.type;

	if (type.toLowerCase().includes('spell') || type.toLowerCase().includes('trap')) {
		return type;
	}

	return props.card.attribute;
});

// Show prices.
const showPrices = ref(false);
const displayPrices = () => showPrices.value = true;
const closePrices = () => showPrices.value = false;

// Show rarities.
const showRarities = ref(false);
const displayRarities = () => {
	if (cardRarities.value.length) {
		showRarities.value = true;
	}
}
const closeRarities = () => showRarities.value = false;

// Show user's collection.
const showUserCollection = ref(false);
const displayUserCollection = () => {
	if(quantities.value.rarities.length) {
		showUserCollection.value = true
	}
};
const closeUserCollection = () => showUserCollection.value = false;

const findQuantityByRarityName = (name) => {
	let quantity = 0;

	const find = quantities.value.rarities.find((quantity) => quantity.rarity_name.toLowerCase() == name.toLowerCase());

	if (find) {
		quantity = find.quantity;
	}

	return quantity;
}

// Show add collection modal.
const showAddModal = ref(false);
const displayAddModal = () => showAddModal.value = true;
const closeAddModal = () => showAddModal.value = false;
const addRarityForm = useForm({
	rarity_id: '',
	quantity: 1,
});

// Display the modal and set this rarity and the default selected rarity.
// Users can still change their rarity within the modal.
const addRarityToCollection = (rarity) => {
	addRarityForm.rarity_id = rarity.id;
	displayAddModal();
}
</script>

<style>
.card {
	align-items: center;
	background-color: #cfcfcf;
	border: 1px solid #fff;
	border-radius: .1875rem;
	display: inline-flex;
	height: 1.75rem;
	justify-content: center;
	width: 1.5rem
}

.card.card-dot:before {
	background-color: #fff
}

.card.active {
	background-color: #b9b9b9;
	border-color: #fff
}

.card.active.card-dot:before {
	background-color: #fff
}

.card-first-set.active {
	background-color: #1d9e74;
	border-color: #fff
}

.card-first-set.active.card-dot:before {
	background-color: #fff
}

.card-squares {
	position: relative;
}

.card-squares::before,
.card-squares::after {
	content: "";
	display: inline-block;
	height: .375rem;
	background-color: #fff;
	position: absolute;
	left: 50%;
	transform: translateX(-50%);
}

/** Top square */
.card-squares::before {
	width: .75rem;
	height: .65rem;
	top: 10%;
}

/** Bottom square */
.card-squares::after {
	width: 1rem;
	bottom: 10%;
}
</style>
