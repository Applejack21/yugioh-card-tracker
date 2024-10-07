<template>
	<AppLayout :title="title">
		<div class="flex-1 grid grid-cols-2 max-w-full m-auto h-screen bg-white">
			<div class="px-10 lg:px-36 2xl:px-44 lg:col-span-1 col-span-2 bg-white my-auto">
				<slot />
			</div>
		</div>

		<div class="background-login hidden lg:block"></div>
	</AppLayout>
</template>

<script setup>
defineProps({
	title: String,
})
</script>

<style>
.background-login {
	background-image: url('/images/login-background.jpg');
	background-repeat: no-repeat;
	background-size: cover;
	background-position: bottom;
	height: 100vh;
	width: 50%;
	position: absolute;
	top: 0;
	right: 0;
}
</style>
