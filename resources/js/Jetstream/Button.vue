<template>
	<button :type="type" :class="[
		'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition',
		getButtonColour()
	]">
		<slot />
	</button>
</template>

<script setup>
const props = defineProps({
	type: {
		type: String,
		default: 'submit',
	},
	colour: {
		type: String,
		default: 'default',
	},
});

const getButtonColour = () => {
	switch (props.colour.toLowerCase()) {
		case 'default': return 'bg-gray-800 text-white hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300'
		case 'primary': return 'bg-primary-light text-white hover:bg-primary active:bg-primary-dark focus:outline-none'
		case 'secondary': return 'bg-secondary-light text-white hover:bg-secondary active:bg-secondary-dark focus:outline-none'
		case 'accent': return 'bg-accent-light text-white hover:bg-accent active:bg-accent-dark focus:outline-none'
	}
}
</script>
