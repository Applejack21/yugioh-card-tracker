<template>
	<Link :href="'#'">
	<div class="relative">
		<div class="flex items-center transition-transform duration-300 ease-in-out hover:scale-[1.1]">
			<img :src="card.image" :alt="`${card.name} card image`" class="mx-auto py-2 text-center rounded-[20p]"
				width="250" height="400">

			<div
				class="absolute z-10 bottom-0 w-full justify-center space-x-16 xl:space-x-0 lg:left-0 lg:right-0 flex xl:justify-between pb-2">
				<div class=" bg-white px-2 truncate text-[12px]">
					<template v-if="latestSet">
						{{ latestSet.code }}
					</template>
					<template v-else>
						N/A
					</template>
				</div>

				<div class="bg-white px-2 max-w-[100px] truncate text-[12px]" :title="card.type">
					{{ card.type }}
				</div>
			</div>
		</div>
	</div>
	</Link>
	<div class="mt-5 xl:flex grid justify-center px-1 grid-cols-3 xl:justify-between mx-auto items-center w-full">
		<div>
			<img :src="card.type_image" class="size-7" :title="typeTitle" />
		</div>
		<div class="text-sm text-gray-500">
			{{ card.race }}
		</div>
		<div class="flex flex-col">
			<div class="text-sm text-gray-500" title="TCG Release Date">
				{{ card.tcg_date_formatted ?? 'N/A' }}
			</div>
			<div class="text-sm text-gray-500" title="OCG Release Date">
				{{ card.ocg_date_formatted ?? 'N/A' }}
			</div>
		</div>
	</div>
	<div class="border-t-2 pt-2 mt-2">
		<div class="xl:flex grid justify-center px-1 grid-cols-3 xl:justify-between mx-auto items-center w-full">
			<div class="flex items-center flex-shrink-0 size-8 justify-center leading-none hover:cursor-pointer"
				title="View Card Rarities" @click.prevent="displayRarities" v-if="cardRarities.length">
				<div>
					<span class="card card-squares card-first-set z-30 relative" />
				</div>
			</div>
			<div class="flex gap-x-5 justify-center items-center w-full">
				<div>
					<MinusIcon class="size-5 text-gray-500 hover:text-black hover:cursor-pointer" />
				</div>
				<div class="text-lg font-semibold">
					5
				</div>
				<div>
					<PlusIcon class="size-5 text-gray-500 hover:text-black hover:cursor-pointer" />
				</div>
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
						<div class="px-1 py-1">
							<MenuItem v-slot="{ active }" @click.prevent="displayPrices">
							<button :class="[
		active ? 'bg-gray-200' : 'text-gray-900',
		'group flex w-full items-center rounded-md px-2 py-2 text-sm disabled:opacity-30',
	]" :disabled="!cardPrices">
								<CurrencyPoundIcon class="size-5 mr-2" :active="active" />
								View Prices
							</button>
							</MenuItem>
							<MenuItem v-slot="{ active }" @click.prevent="displayRarities">
							<button :class="[
		active ? 'bg-gray-200' : 'text-gray-900',
		'group flex w-full items-center rounded-md px-2 py-2 text-sm disabled:opacity-30',
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

	<!-- Price Modal -->
	<DialogModal :show="showPrices" @close="closePrices" maxWidth="5xl">
		<template #title>
			Prices for {{ card.name }}
		</template>
		<template #content>
			<div>
				<span class="text-sm">
					Prices correct as of {{ dayjs(cardPrices.updated_at).format('YYYY-MM-DD @ HH:mm:ss UTC') }}
				</span>
			</div>
			<div class="grid grid-cols-5 mt-5 gap-5">
				<div>
					<Card>
						<CardBody>
							<div class="flex flex-col mx-auto items-center justify-center">
								Cardmarket Price
								<div class="flex items-center mt-2 gap-x-2">
									<CurrencyEuroIcon class="size-7" />
									{{ cardPrices.cardmarket_price }}
								</div>
							</div>
						</CardBody>
					</Card>
				</div>
				<div>
					<Card>
						<CardBody>
							<div class="flex flex-col mx-auto items-center justify-center">
								CoolStuffInc Price
								<div class="flex items-center mt-2 gap-x-2">
									<CurrencyDollarIcon class="size-7" />
									{{ cardPrices.coolstuffinc_price }}
								</div>
							</div>
						</CardBody>
					</Card>
				</div>
				<div>
					<Card>
						<CardBody>
							<div class="flex flex-col mx-auto items-center justify-center">
								Tcgplayer Price
								<div class="flex items-center mt-2 gap-x-2">
									<CurrencyDollarIcon class="size-7" />
									{{ cardPrices.tcgplayer_price }}
								</div>
							</div>
						</CardBody>
					</Card>
				</div>
				<div>
					<Card>
						<CardBody>
							<div class="flex flex-col mx-auto items-center justify-center">
								eBay Price
								<div class="flex items-center mt-2 gap-x-2">
									<CurrencyDollarIcon class="size-7" />
									{{ cardPrices.ebay_price }}
								</div>
							</div>
						</CardBody>
					</Card>
				</div>
				<div>
					<Card>
						<CardBody>
							<div class="flex flex-col mx-auto items-center justify-center">
								Amazon Price
								<div class="flex items-center mt-2 gap-x-2">
									<CurrencyDollarIcon class="size-7" />
									{{ cardPrices.amazon_price }}
								</div>
							</div>
						</CardBody>
					</Card>
				</div>
			</div>
		</template>
	</DialogModal>

	<!-- Rarities Modal -->
	<DialogModal :show="showRarities" @close="closeRarities" maxWidth="5xl">
		<template #title>
			Rarities
		</template>
		<template #content>
			<div>
				<span class="text-sm">
					A list of card rarities available to collect.
				</span>
			</div>
			<div class="grid grid-cols-3 mt-5 gap-5">
				<div v-for="rarity in cardRarities">
					<Card>
						<CardBody>
							<div class="flex flex-col mx-auto items-center justify-center">
								<span>
									{{ rarity.name }}
								</span>
								<span>
									{{ rarity.code !== '' ? rarity.code : '(N/A)' }}
								</span>
							</div>
						</CardBody>
					</Card>
					<div class="flex gap-x-5 justify-center items-center w-full mt-2">
						<div>
							<MinusIcon class="size-5 text-gray-500 hover:text-black hover:cursor-pointer" />
						</div>
						<div class="text-lg font-semibold">
							5
						</div>
						<div>
							<PlusIcon class="size-5 text-gray-500 hover:text-black hover:cursor-pointer" />
						</div>
					</div>
				</div>
			</div>
		</template>
	</DialogModal>
</template>

<script setup>
import { Card, CardBody } from '@/Components/Card';
import DialogModal from '@/Jetstream/DialogModal.vue';
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { CurrencyDollarIcon, CurrencyEuroIcon, CurrencyPoundIcon, SwatchIcon } from '@heroicons/vue/24/outline';
import { EllipsisVerticalIcon, MinusIcon, PlusIcon } from '@heroicons/vue/24/solid';
import dayjs from "dayjs";
import { computed, ref } from 'vue';

const props = defineProps({
	card: Object,
});

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

const backgroundColour = (index) => {
	if (index == 0) {

	}

	if (index == 1) {
		return '!bg-blue-600';
	}

	if (index == 2) {

	}

	return '';
}

const showPrices = ref(false);
const displayPrices = () => showPrices.value = true;
const closePrices = () => showPrices.value = false;

const showRarities = ref(false);
const displayRarities = () => showRarities.value = true;
const closeRarities = () => showRarities.value = false;
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
	background-color: #fa7035;
	border-color: #fff
}

.card-first-set.active.card-dot:before {
	background-color: #fff
}

.card-second-set.active {
	background-color: #5bc0de;
	border-color: #fff
}

.card-second-set.active.card-dot:before {
	background-color: #fff
}

.card-third-set.active {
	background-color: #e663ca;
	border-color: #fff
}

.card-third-set.active.card-dot:before {
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
