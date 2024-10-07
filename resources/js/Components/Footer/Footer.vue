<template>
	<footer class="bg-white pt-32 pb-[100px] shadow-[0_-4px_10px_10px_rgba(0,_0,_0,_0.05)] relative -mb-2">
		<Container class="space-y-12 divide-y divide-grey-300">
			<div class="grid grid-cols-1 md:grid-cols-12 gap-5">
				<div class="flex flex-col w-full lg:flex-none md:col-span-3 mb-10 lg:mb-0">
					<div class="sm:w-1/2 md:w-full mb-2 mx-auto">
						<img src="/images/logo-transparent.png">
					</div>
					<div>
						<p class="leading-tight mb-5 flex justify-center items-center">
							Track your collection today.
						</p>
					</div>
					<ul class="space-y-3 text-sm mx-auto">
						<li v-for="link in socialLinks">
							<a :href="link.href" target="_blank"
								class="flex items-center space-x-3 transition-colors hover:fill-secondary hover:text-secondary group-focus:text-secondary">
								<component :is="link.icon" class="size-8" />
								<span class="text-lg">
									{{ link.title }}
								</span>
							</a>
						</li>
					</ul>
				</div>
				<div
					class="flex flex-col justify-center mx-auto  text-center md:mx-0 gap-y-10 md:grid md:grid-cols-2 md:col-span-8 md:col-start-5 md:gap-x-10 lg:grid-cols-4">
					<FooterMenu v-for="menu in footerMenus" :title="menu.title" :links="menu.links" />
				</div>
			</div>
		</Container>
	</footer>
</template>

<script setup>
import { FooterMenu } from '@/Components/Footer';
import { DiscordIcon } from '@/Components/Icons';

const footerMenus = [
	{
		title: 'Cards',
		links: [
			{ title: 'Cards', href: route('card.index') },
			{ title: 'Card Sets', href: route('card-set.index') },
		],
	},
	{
		title: 'Profiles',
		links: [
			{ title: 'Your Profile', href: route('profile.show') },
			{ title: 'Your Dashboard', href: '#' },
			{ title: 'Public Profiles', href: '#' },
		],
	},
	{
		title: 'About',
		links: [
			{ title: 'What is YGO Collector?', href: '#' },
		],
	},
	{
		title: 'Support',
		links: [
			{ title: 'How does this site work?', href: '#' },
		],
	},
]

const socialLinks = [
	{ icon: DiscordIcon, title: 'Discord', href: '#' },
]
</script>
