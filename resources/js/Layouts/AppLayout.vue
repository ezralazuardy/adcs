<template>
    <Head :title="title"/>
    <div class="h-screen flex overflow-hidden bg-gray-100 ">
        <!-- Mobile Sidebar -->
        <TransitionRoot as="template" :show="sidebarOpen">
            <Dialog as="div" static class="fixed inset-0 flex z-40 md:hidden" @close="sidebarOpen = false"
                    :open="sidebarOpen">
                <TransitionChild as="template" enter="transition-opacity ease-linear duration-300"
                                 enter-from="opacity-0" enter-to="opacity-100"
                                 leave="transition-opacity ease-linear duration-300" leave-from="opacity-100"
                                 leave-to="opacity-0">
                    <DialogOverlay class="fixed inset-0 bg-gray-600 bg-opacity-75"/>
                </TransitionChild>
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform"
                                 enter-from="-translate-x-full" enter-to="translate-x-0"
                                 leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0"
                                 leave-to="-translate-x-full">
                    <div class="relative flex-1 flex flex-col max-w-xs w-full pt-5 bg-pink-700">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0"
                                         enter-to="opacity-100" leave="ease-in-out duration-300"
                                         leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute top-0 right-0 -mr-12 pt-2">
                                <button type="button"
                                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                                        @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <x-icon class="h-6 w-6 text-white" aria-hidden="true"/>
                                </button>
                            </div>
                        </TransitionChild>
                        <div class="flex-shrink-0 flex items-center px-4">
                            <span class="text-2xl font-bold text-white">ADCS</span>
                        </div>
                        <div
                            class="flex-1 h-full overflow-y-auto scrollbar-thin scrollbar-thumb-pink-800 scrollbar-track-pink-200 mt-4 pb-4">
                            <nav>
                                <div class="px-4 space-y-2 pt-2">
                                    <span class="text-base text-white font-semibold">Menu</span>
                                    <Link v-for="item in this.menuNavigations" :key="item.name" :href="route(item.href)"
                                          :class="[route().current(item.href) || (item.currents && item.currents.includes(route().current())) ? 'bg-pink-800 text-white' : 'text-pink-100 hover:bg-pink-600', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']">
                                        <component :is="item.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-pink-300"
                                                   aria-hidden="true"/>
                                        {{ item.name }}
                                    </Link>
                                </div>
                                <div class="px-4 space-y-2 pt-6">
                                    <span class="text-base text-white font-semibold">Kelola</span>
                                    <Link v-for="item in this.manageNavigations" :key="item.name"
                                          :href="route(item.href)"
                                          :class="[route().current(item.href) ? 'bg-pink-800 text-white' : 'text-pink-100 hover:bg-pink-600', 'group flex items-center px-2 py-2 text-base font-medium rounded-md']">
                                        <component :is="item.icon" class="mr-4 flex-shrink-0 h-6 w-6 text-pink-300"
                                                   aria-hidden="true"/>
                                        {{ item.name }}
                                    </Link>
                                </div>
                            </nav>
                        </div>
                    </div>
                </TransitionChild>
                <div class="flex-shrink-0 w-14" aria-hidden="true">
                    <!-- Dummy element to force sidebar to shrink to fit close icon -->
                </div>
            </Dialog>
        </TransitionRoot>
        <!-- Desktop Sidebar -->
        <div class="hidden bg-pink-700 md:flex md:flex-shrink-0">
            <div class="flex flex-col w-64">
                <div
                    class="flex flex-col flex-grow pt-5 pb-4 overflow-y-auto scrollbar-thin scrollbar-thumb-pink-800 scrollbar-track-pink-200">
                    <div class="flex items-center flex-shrink-0 px-4">
                        <span class="text-2xl font-bold text-white">ADCS</span>
                    </div>
                    <div class="flex-1 flex flex-col">
                        <nav>
                            <div class="flex-1 px-4 space-y-2 pt-6">
                                <span class="text-base text-white font-semibold">Menu</span>
                                <Link v-for="item in menuNavigations" :key="item.name" :href="route(item.href)"
                                      :class="[route().current(item.href) || (item.currents && item.currents.includes(route().current())) ? 'bg-pink-800 text-white' : 'text-pink-100 hover:bg-pink-600', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                                    <component :is="item.icon" class="mr-3 flex-shrink-0 h-6 w-6 text-pink-300"
                                               aria-hidden="true"/>
                                    {{ item.name }}
                                </Link>
                            </div>
                            <div class="flex-1 px-4 space-y-2 pt-6">
                                <span class="text-base text-white font-semibold">Kelola</span>
                                <Link v-for="item in manageNavigations" :key="item.name" :href="route(item.href)"
                                      :class="[route().current(item.href) ? 'bg-pink-800 text-white' : 'text-pink-100 hover:bg-pink-600', 'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
                                    <component :is="item.icon" class="mr-3 flex-shrink-0 h-6 w-6 text-pink-300"
                                               aria-hidden="true"/>
                                    {{ item.name }}
                                </Link>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
                <button type="button"
                        class="px-4 border-r border-gray-200 text-gray-500 md:hidden"
                        @click="sidebarOpen = true">
                    <span class="sr-only">Buka sidebar</span>
                    <menu-alt2-icon class="h-6 w-6" aria-hidden="true"/>
                </button>
                <div class="flex-1 px-4 flex justify-between">
                    <div class="flex-1 flex lg:pl-4">
                        <h1 class="text-xl font-bold text-gray-900 lg:py-4 py-5">
                            {{ title }}
                        </h1>
                    </div>
                    <div class="ml-4 flex items-center md:ml-6 mr-0 lg:mr-8">
                        <div class="icon-badge-container">
                            <button
                                @click="showNotificationPanel"
                                class="bg-white p-1 icon-badge-icon rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 mr-4">
                                <span class="sr-only">Lihat notifikasi</span>
                                <BellIcon class="h-6 w-6" aria-hidden="true"/>
                            </button>
                            <div v-if="this.$page.props.unreadNotificationCount > 0" class="icon-badge">{{
                                    this.$page.props.unreadNotificationCount > 6 ? '6+' : this.$page.props.unreadNotificationCount
                                }}
                            </div>
                        </div>
                        <Menu as="div" class="ml-3 relative">
                            <div>
                                <MenuButton
                                    class="max-w-xs bg-white flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500">
                                    <span class="sr-only">Buka menu</span>
                                    <img v-if="$page.props.jetstream.managesProfilePhotos"
                                         class="h-8 w-8 rounded-full"
                                         :src="$page.props.user.profile_photo_url"
                                         :alt="$page.props.user.name"/>
                                </MenuButton>
                            </div>
                            <transition enter-active-class="transition ease-out duration-100"
                                        enter-from-class="transform opacity-0 scale-95"
                                        enter-to-class="transform opacity-100 scale-100"
                                        leave-active-class="transition ease-in duration-75"
                                        leave-from-class="transform opacity-100 scale-100"
                                        leave-to-class="transform opacity-0 scale-95">
                                <MenuItems
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white">
                                    <Link :href="route('profile.show')"
                                          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Profil Saya
                                    </Link>
                                    <Link v-if="$page.props.jetstream.hasApiFeatures"
                                          class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                          :href="route('api-tokens.index')">
                                        API Tokens
                                    </Link>
                                    <form method="POST" @submit.prevent="logout">
                                        <button
                                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left w-full">
                                            Keluar
                                        </button>
                                    </form>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                </div>
            </div>
            <main
                class="flex-1 relative overflow-x-hidden overflow-y-auto focus:outline-none scrollbar-thin scrollbar-thumb-pink-800 scrollbar-track-pink-200">
                <div class="max-w-7xl mx-auto py-8 px-6 lg:px-8">
                    <slot></slot>
                </div>
            </main>
        </div>
    </div>
    <notification-panel :show="showingNotificationPanel" @close="closeNotificationPanel"/>
</template>

<script>
import {defineComponent, ref} from 'vue'
import {Head, Link} from '@inertiajs/inertia-vue3'
import {Dialog, DialogOverlay, Menu, MenuButton, MenuItems, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {
    BellIcon,
    CogIcon,
    ChipIcon,
    MenuAlt2Icon,
    SearchIcon,
    XIcon
} from '@heroicons/vue/outline'
import NotificationPanel from '@/Layouts/NotificationPanel'

const navigations = {
    administrator: {
        menu: [
            {name: 'Drones', href: 'drones.index', icon: ChipIcon}
        ],
        manage: [
            {name: 'Pengaturan', href: 'profile.show', icon: CogIcon}
        ]
    },
    operator: {
        menu: [],
        manage: [
            {name: 'Pengaturan', href: 'profile.show', icon: CogIcon}
        ]
    }
}

export default defineComponent({
    props: {
        title: String
    },
    components: {
        NotificationPanel,
        Head,
        Link,
        Dialog,
        DialogOverlay,
        Menu,
        MenuButton,
        MenuItems,
        TransitionChild,
        TransitionRoot,
        BellIcon,
        MenuAlt2Icon,
        SearchIcon,
        XIcon,
        ChipIcon,
        CogIcon
    },
    setup() {
        const sidebarOpen = ref(false)
        return {
            sidebarOpen
        }
    },
    data() {
        return {
            menuNavigations: this.$page.props.user.role_id === 1 ? navigations.administrator.menu : navigations.operator.menu,
            manageNavigations: this.$page.props.user.role_id === 1 ? navigations.administrator.manage : navigations.operator.manage,
            showingNotificationPanel: false
        }
    },
    methods: {
        logout() {
            this.$inertia.post(route('logout'), {}, {
                replace: false,
                preserveState: false,
                preserveScroll: false
            })
        },
        showNotificationPanel() {
            this.showingNotificationPanel = true
        },
        closeNotificationPanel() {
            this.showingNotificationPanel = false
        }
    }
})
</script>
