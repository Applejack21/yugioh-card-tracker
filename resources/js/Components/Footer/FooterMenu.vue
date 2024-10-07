<template>
	<div>
		<div class="h-[34px] mb-2 lg:mb-6">
			<h3 class="text-2xl leading-none">
				{{ title }}
			</h3>
		</div>
		<ul class="space-y-6">
			<li v-for="link in links" class="leading-tight text-lg">
				<Link :href="link.href" class="hover:text-secondary focus-within:text-secondary transition-colors">
				{{ link.title }}
				</Link>
			</li>
		</ul>
	</div>
</template>

<script setup>
defineProps({
	title: String,
	links: {
		type: Array,
		default: () => [],
	},
})
</script>
