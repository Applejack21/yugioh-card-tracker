<template>
	<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-5" :class="colSpan">
		<div v-for="item in items" :class="[colSpan, {
			'flex': addFlex,
		}]">
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
	addFlex: {
		type: Boolean,
		default: true,
	},
	items: {
		type: Array,
		default: () => []
	}
})
</script>
