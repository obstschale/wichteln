<template>
    <div>
        <div class="h-2 bg-green-800"></div>

        <section id="top" class="py-16 bg-white bg-[url('data:image/svg+xml,%3Csvg%20xmlns=%27http://www.w3.org/2000/svg%27%20width=%2780%27%20height=%2780%27%20viewBox=%270%200%2080%2080%27%3E%3Cg%20fill=%27%23d42426%27%20fill-opacity=%27.1%27%3E%3Cpath%20fill-rule=%27evenodd%27%20d=%27M11%200l5%2020H6l5-20zm42%2031a3%203%200%201%200%200-6%203%203%200%200%200%200%206zM0%2072h40v4H0v-4zm0-8h31v4H0v-4zm20-16h20v4H20v-4zM0%2056h40v4H0v-4zm63-25a3%203%200%201%200%200-6%203%203%200%200%200%200%206zm10%200a3%203%200%201%200%200-6%203%203%200%200%200%200%206zM53%2041a3%203%200%201%200%200-6%203%203%200%200%200%200%206zm10%200a3%203%200%201%200%200-6%203%203%200%200%200%200%206zm10%200a3%203%200%201%200%200-6%203%203%200%200%200%200%206zm-30%200a3%203%200%201%200%200-6%203%203%200%200%200%200%206zm-28-8a5%205%200%200%200-10%200h10zm10%200a5%205%200%200%201-10%200h10zM56%205a5%205%200%200%200-10%200h10zm10%200a5%205%200%200%201-10%200h10zm-3%2046a3%203%200%201%200%200-6%203%203%200%200%200%200%206zm10%200a3%203%200%201%200%200-6%203%203%200%200%200%200%206zM21%200l5%2020H16l5-20zm43%2064v-4h-4v4h-4v4h4v4h4v-4h4v-4h-4zM36%2013h4v4h-4v-4zm4%204h4v4h-4v-4zm-4%204h4v4h-4v-4zm8-8h4v4h-4v-4z%27/%3E%3C/g%3E%3C/svg%3E')]">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl font-bold text-gray-900">{{ group.name }}</h1>
                <h2 class="text-xl text-gray-700 mt-2">am {{ formattedDate }}</h2>
            </div>
        </section>

        <div class="container mx-auto px-4 -mt-8">
            <div class="max-w-3xl mx-auto">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div v-show="groupStatus === 'started'" class="bg-green-800 text-white px-4 py-3 font-medium">
                        Ausgelost: Teilnehmer wurden benachrichtigt
                    </div>

                    <div class="p-6">
                        <div class="text-center mb-6">
                            <p class="text-2xl font-light text-gray-900">Teilnehmer</p>
                            <div class="inline-flex items-center mt-2">
                                <span class="px-3 py-1 bg-blue-500 text-white text-sm rounded-l-full">Anzahl</span>
                                <span class="px-3 py-1 bg-gray-200 text-gray-700 text-sm rounded-r-full">{{ totalMembers }}</span>
                            </div>
                        </div>

                        <div v-if="buddy.name" class="text-center my-6 py-4 border-y border-gray-300 space-y-2">
                            <span class="block">Dein Wichtel wurde gezogen.<br>Du darfst folgende Person beglücken:</span>
                            <span class="block text-2xl font-bold">{{ buddy.name }}</span>
                            <span class="block"><strong>Wunschzettel</strong>: {{ buddy.wishlist }}</span>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200">
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Name</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Status</th>
                                        <th class="text-left py-3 px-4 font-medium text-gray-700">Wunschzettel</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="member in members"
                                        :key="member.id"
                                        :class="{ 'bg-green-800 text-white': isNew(member) }"
                                        class="border-b border-gray-100 transition-colors duration-1000"
                                    >
                                        <td class="py-3 px-4">{{ member.name }}</td>
                                        <td class="py-3 px-4">
                                            <span
                                                class="inline-block px-3 py-1 text-sm rounded-md"
                                                :class="statusColor(member)"
                                            >
                                                {{ status(member) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4">
                                            <span>{{ wishlist(member) }}</span>
                                            <button
                                                v-show="member.id === userId && groupStatus !== 'started'"
                                                class="ml-2 px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 rounded-md"
                                                @click="toggleWishlistModal"
                                            >
                                                Wunschzettel bearbeiten
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <hr class="my-6 border-gray-200">

                        <member-add-form
                            v-show="groupStatus !== 'started' && isAdmin"
                            :group="group"
                            @newMember="addMember"
                        ></member-add-form>
                    </div>

                    <div v-show="groupStatus !== 'started' && isAdmin" class="border-t border-gray-200">
                        <button
                            class="w-full py-3 bg-yellow-500 text-white font-medium hover:bg-yellow-600 transition-colors"
                            @click="startRaffle"
                        >
                            Auslosung Starten
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wishlist Modal -->
        <div v-show="showWishlist" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/50" @click="toggleWishlistModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Dein Wunschzettel</h3>
                        <button
                            class="text-gray-400 hover:text-gray-600"
                            @click="toggleWishlistModal"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <div v-show="wishlistError" class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                            Es gab Probleme beim speichern des Wunschzettel. Lade einmal die Seite neu und probier es bitte noch mal.
                        </div>
                        <textarea
                            v-model="user.wishlist"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                            rows="5"
                            placeholder="Wunschzettel schreiben"
                        ></textarea>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200">
                        <button
                            class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700"
                            @click="saveWishlist"
                        >
                            Speichern
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import dayjs from 'dayjs';
import 'dayjs/locale/de';

dayjs.locale('de');

export default {
    name: "WichtelgroupView",
    props: ['userId', 'group', 'isAdmin', 'buddy'],
    data() {
        return {
            members: [],
            newMember: {},
            groupStatus: '',
            showWishlist: false,
            user: {
                wishlist: '',
            },
            wishlistError: false
        };
    },
    mounted() {
        this.members = this.group.users;
        this.groupStatus = this.group.status;
        const currentUser = this.members.find((member) => member.id === this.userId);
        this.user.wishlist = currentUser ? currentUser.pivot.wishlist : '';
    },
    computed: {
        formattedDate() {
            return dayjs(this.group.date).format('DD. MMMM YYYY');
        },
        token() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('token');
        },
        totalMembers() {
            return this.members.length;
        },
    },
    methods: {
        addMember(member) {
            this.members.push(member);
            setTimeout(() => {
                this.newMember = member;
                setTimeout(() => {
                    this.newMember = {};
                }, 5000);
            }, 100);
        },
        isNew(member) {
            return this.newMember.id === member.id;
        },
        status(member) {
            if (!member.pivot) {
                return 'Eingeladen';
            }

            switch (member.pivot.status) {
                case 'approved':
                    return 'Nimmt teil';
                default:
                case 'invited':
                    return 'Eingeladen';
            }
        },
        statusColor(member) {
            switch (this.status(member)) {
                case 'Eingeladen':
                    return 'bg-yellow-100 text-yellow-800';
                case 'Nimmt teil':
                    return 'bg-green-100 text-green-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        },
        wishlist(member) {
            if (!member.pivot) {
                return "🚫";
            }

            if (member.pivot.wishlist !== null) {
                return "✅";
            }

            return "🚫";
        },
        toggleWishlistModal() {
            this.showWishlist = !this.showWishlist;
        },
        startRaffle() {
            const startRaffle = confirm("Möchtest du die Auslosung starten?\nHiermit werden all Teilnehmer benachrichtigt. Dieser Schritt kann nicht rückgängig gemacht werden!");

            if (startRaffle) {
                axios.put(`/api/v1/wichtelgroups/${this.group.id}`, {
                    name: this.group.name,
                    date: this.group.date,
                    status: 'started'
                }, {
                    headers: { Authorization: `Bearer ${this.token}` }
                }).then(() => {
                    this.groupStatus = 'started';
                });
            }
        },
        saveWishlist() {
            this.wishlistError = false;
            axios.put(`/api/v1/wichtelgroups/${this.group.id}/wichtelmembers/${this.userId}`, {
                wishlist: this.user.wishlist
            }, {
                headers: { Authorization: `Bearer ${this.token}` }
            }).then(() => {
                this.toggleWishlistModal();
            }).catch(() => {
                this.wishlistError = true;
            });
        }
    }
};
</script>
