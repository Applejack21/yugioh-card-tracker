<template>
	<AppLayout :title="title" :addMargin="true">
		<template #header>
			<h2 class="font-semibold text-lg text-gray-800 leading-tight">
				{{ title }}
			</h2>
		</template>
		<Container>
			<div class="sm:px-6 lg:px-8">
				<div class="px-4 py-8 sm:px-0">
					Lorem Ipsum.
				</div>
			</div>
		</Container>
	</AppLayout>
</template>

<script setup>
const props = defineProps({
	title: String,
});
</script>
