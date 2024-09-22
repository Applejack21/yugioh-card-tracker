<template>
	<div class="flex xl:col-span-4">
		<div class="w-full rounded-[11px] border-gray-500 bg-white shadow-md flex flex-col h-full">
			<div class="flex w-full flex-col px-6 py-[26px] flex-grow">
				<div
					class="mx-auto -mt-5 mb-8 flex min-h-[44px] flex-col items-center space-x-0 space-y-5 md:flex md:flex-row">
					<div class="flex w-full flex-col items-center space-y-2">
						<div>
							<h2 class="text-center text-xl">
								<img :src="set.image" :alt="`${set.name} image`" class="mx-auto py-2 text-center"
									width="150" />
							</h2>
						</div>
					</div>
					<div class="md:ml-auto"></div>
				</div>
				<div class="mt-auto">
					<div class="mt-auto w-[350px] px-5 mb-5 text-lg">
						<div class="flex flex-col">
							<p class="text-center">
								{{ set.name }}
							</p>
							<div class="mx-auto">
								<span class="text-secondary font-bold">
									{{ set.total_cards }} card(s)
								</span>
							</div>
						</div>
					</div>
					<div class="flex w-full items-center justify-between">

						<span class="text-gray-500">
							{{ set.code }}
						</span>
						<span class="text-gray-500">
							{{ set.release_date_formatted }}
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script setup>
const props = defineProps({
	set: Object,
});
</script>
