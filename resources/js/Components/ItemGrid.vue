<template>
	<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-5" :class="colSpan">
		<div v-for="item in items" class="flex" :class="colSpan">
			<slot :item="item" />
		</div>
	</div>
</template>

<script setup>
defineProps({
	colSpan: {
		type: String,
		default: 'xl:col-span-4'
	},
	items: {
		type: Array,
		default: () => []
	}
})
</script>
