<template>
	<AppLayout :title="title">
		<template #header>
			<h2 class="font-semibold text-lg text-gray-800 leading-tight">
				{{ title }}
			</h2>
			<p>
				A list of cards that have been released. Default ordered by most recent (TCG wise).
			</p>
		</template>
		<template #main>
			<div class="mx-auto sm:px-6 lg:px-8">
				<div class="px-4 py-8 sm:px-0">
					<ItemGrid :items="cards.data" :addFlex="false"  colSpan="xl:col-span-2 gap-y-10" v-slot="{ item }">
						<Card :card="item" />
					</ItemGrid>
				</div>
				<Pagination :links="cards.meta" />
			</div>
		</template>
	</AppLayout>
</template>

<script setup>
import { ItemGrid, Card } from '@/Components';
import { Pagination } from '@/Components/Pagination';

const props = defineProps({
	title: String,
	cards: Object,
})
</script>
