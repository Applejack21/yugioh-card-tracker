<template>

	<Head :title="title" />
	<div class="min-h-full">
		<Disclosure as="nav" class="bg-primary border-b border-primary sticky top-0 z-50" v-slot="{ open }">
			<div class="px-4 sm:px-6 lg:px-8">
				<div class="flex justify-between h-16">
					<div class="flex">
						<!-- Logo for homepage on larger screens -->
						<div class="hidden sm:-my-px sm:flex sm:space-x-4 lg:space-x-8">
							<img @click.prevent="router.visit(route('homepage.index'))"
								src="/images/logo-transparent.png"
								class="h-auto sm:w-1/6 lg:w-1/4 my-auto hover:cursor-pointer" />
							<Link v-for="item in navigation" :key="item.name" :href="item.href"
								:class="[item.current ? 'border-white-500 text-white' : 'border-transparent text-white hover:border-secondary-light hover:text-white', 'inline-flex items-center px-1 pt-1 border-b-4 text-lg font-medium']"
								:aria-current="item.current ? 'page' : undefined">
							{{ item.name }}
							</Link>
						</div>
					</div>
					<!-- Auth -->
					<div class="ml-auto flex items-center text-white text-lg">
						<div class="hidden sm:block">
							<div class="ml-4 flex items-center md:-ml-16 lg:-ml-0">
								<!-- Profile dropdown -->
								<Menu as="div" class="relative ml-3">
									<div>
										<MenuButton
											class="relative flex max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
											<span class="absolute -inset-1.5" />
											<span class="sr-only">Open user menu</span>
											<img v-if="user" class="size-28 md:size-10 rounded-full"
												:src="user?.profile_photo_url" alt="" />
											<UserCircleIcon v-else class="size-10 rounded-full" />
										</MenuButton>
									</div>
									<transition enter-active-class="transition ease-out duration-100"
										enter-from-class="transform opacity-0 scale-95"
										enter-to-class="transform opacity-100 scale-100"
										leave-active-class="transition ease-in duration-75"
										leave-from-class="transform opacity-100 scale-100"
										leave-to-class="transform opacity-0 scale-95">
										<MenuItems
											class="absolute right-0 mt-3.5 z-10 w-60 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
											<MenuItem
												v-for="(item, index) in user ? userNavigation : notLoggedInNavigation"
												:key="item.name" v-slot="{ active }">
											<Link :href="item.href" :method="item.method ?? 'get'"
												class="flex gap-x-5 items-center p-4 text-sm text-gray-700 w-full"
												:class="[{ 'bg-gray-100': active }]">
											<component :is="item.icon" class="size-5 fill-black" />
											{{ item.name }}
											</Link>
											</MenuItem>
										</MenuItems>
									</transition>
								</Menu>
							</div>
						</div>
					</div>
					<div class="-mr-2 flex w-full items-center sm:hidden">

						<!-- Show logo on mobile -->
						<div class="sm:hidden flex w-full items-center justify-center">
							<img @click.prevent="router.visit(route('homepage.index'))"
								src="/images/logo-transparent.png"
								class="h-auto max-w-[250px] my-auto hover:cursor-pointer pl-3" />
						</div>

						<!-- Mobile menu button -->
						<DisclosureButton
							class="ml-auto bg-white inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100">
							<span class="sr-only">Open main menu</span>
							<Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
							<XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
						</DisclosureButton>
					</div>
				</div>
			</div>

			<!-- Mobile menu links -->
			<DisclosurePanel class="sm:hidden">
				<div class="pt-2 pb-3 space-y-1">
					<Link v-for="item in navigation" :key="item.name" :href="item.href"
						:class="[item.current ? 'bg-secondary border-white-500 text-white' : 'border-transparent text-white hover:bg-secondary hover:text-white', 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium']"
						:aria-current="item.current ? 'page' : undefined">
					{{ item.name }}
					</Link>
				</div>
				<hr>
				<!-- User navigation for mobile -->
				<div class="pt-2 pb-3 space-y-1">
					<Link v-for="item in user ? userNavigation : notLoggedInNavigation" :key="item.name"
						:href="item.href" :method="item.method ?? 'get'"
						:class="[item.current ? 'bg-secondary border-white-500 text-white' : 'border-transparent text-white hover:bg-secondary hover:text-white', 'block pl-3 pr-4 py-2 border-l-4 text-base font-medium']"
						:aria-current="item.current ? 'page' : undefined">
					{{ item.name }}
					</Link>
				</div>
			</DisclosurePanel>

		</Disclosure>

		<div>
			<!-- Page Heading -->
			<header v-if="$slots.header" class="bg-white shadow">
				<Container>
					<div class="py-6 px-4 sm:px-6 lg:px-8">
						<slot name="header" />
					</div>
				</Container>
			</header>

			<!-- Page Content -->
			<main class="relative" :class="[{ 'mb-10': addMargin }]">
				<slot />
			</main>
		</div>
		<Footer />

	</div>
</template>

<script setup>
import { Footer } from '@/Components/Footer';
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Bars3Icon, UserCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline';
import { ArrowRightEndOnRectangleIcon, UserPlusIcon } from '@heroicons/vue/24/solid';
import { Head, Link, router, usePage } from "@inertiajs/vue3";
import { computed, provide, ref, watch } from "vue";
import { useToast } from '@/composables/useToast.js';

const props = defineProps({
	title: String,
	addMargin: Boolean,
});

const {
	watchMessage
} = useToast();

watchMessage();

const user = computed(() => usePage().props?.auth?.user);
provide('user', user);

const navigation = ref([
	{
		name: 'Cards',
		href: route('card.index'),
		current: route().current('card.*')
	},
	{
		name: 'Card Sets',
		href: route('card-set.index'),
		current: route().current('card-set.*')
	},
	{
		name: 'Public Profiles',
		href: '#',
		current: false,
	},
]);

const userNavigation = [
	{
		name: 'Your Profile',
		href: route('profile.show'),
		current: route().current('profile.show'),
	},
	{
		name: 'Sign out',
		method: 'post',
		href: route('logout')
	},
];

const notLoggedInNavigation = [
	{
		name: 'Login',
		icon: ArrowRightEndOnRectangleIcon,
		href: route('login'),
		current: route().current('login'),
	},
	{
		name: 'Create an account',
		icon: UserPlusIcon,
		href: route('register'),
		current: route().current('register'),
	},
];

</script>
