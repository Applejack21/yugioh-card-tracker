<template>
	<Card>
		<CardBody class="flex flex-col">
			<CardHeader class="mx-auto">
				<img src="https://placecats.com/200/200" class="mx-auto py-2 text-center" />
				<div class="w-[350px] px-5">
					<p class="text-center truncate">
						{{ set.name }}
					</p>
				</div>
			</CardHeader>
			<div class="flex justify-between mt-auto w-full items-center">
				<span class="text-gray-500">
					{{ set.code }}
				</span>
				<div>
					<span class="text-xl text-secondary font-bold">
						{{ set.total_cards }} cards
					</span>
				</div>
				<span class="text-gray-500">
					{{ set.release_date_formatted }}
				</span>
			</div>
		</CardBody>
	</Card>
</template>

<script setup>
import { Card, CardHeader, CardBody } from '@/Components/Card';
import { RectangleStackIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
	set: Object,
});
</script>
