<template>
	<DialogModal :show="show" @close="close" maxWidth="5xl">
		<template #title>
			Prices for "{{ card.name }}"
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
</template>

<script setup>
import { Card, CardBody } from '@/Components/Card';
import DialogModal from "@/Jetstream/DialogModal.vue";
import { CurrencyDollarIcon, CurrencyEuroIcon } from '@heroicons/vue/24/outline';
import dayjs from "dayjs";

const props = defineProps({
	card: Object,
	cardPrices: Object,
	show: Boolean,
});

const emit = defineEmits(['close']);

const close = () => emit('close');
</script>
