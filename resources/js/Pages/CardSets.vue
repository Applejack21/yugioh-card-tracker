<template>
	<AppLayout :title="title">
		<template #header>
			<h2 class="font-semibold text-lg text-gray-800 leading-tight">
				{{ title }}
			</h2>
			<p>
				A list of card sets that have been released.
			</p>
		</template>
		<template #main>
			<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
				<div class="px-4 py-8 sm:px-0">
					<ItemGrid :items="cardSets.data" v-slot="{ item }">
						<CardSetCard :set="item" />
					</ItemGrid>
				</div>
				<Pagination :links="cardSets.meta"	/>
			</div>
		</template>
	</AppLayout>
</template>

<script setup>
import { CardSetCard, ItemGrid } from '@/Components';
import { Pagination } from '@/Components/Pagination';

const props = defineProps({
	title: String,
	cardSets: Object,
})
</script>
