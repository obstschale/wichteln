<template>
    <section class="wichtelgroup-page min-h-screen flex flex-col relative overflow-hidden">
        <!-- Animated Snowflakes -->
        <div class="snowflakes" aria-hidden="true">
            <div v-for="i in 12" :key="i" class="snowflake">❄</div>
        </div>

        <!-- Navigation -->
        <nav class="py-6 relative z-10">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <a class="text-2xl font-bold text-white drop-shadow-lg flex items-center gap-2" href="/">
                    <span class="text-3xl">🎁</span>
                    <span>Wichtel.me</span>
                </a>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 relative z-10 py-8">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto">
                    <!-- Header Card -->
                    <div class="text-center mb-8">
                        <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-3 drop-shadow-xl">
                            {{ group.name }}
                        </h1>
                        <p class="text-xl text-white/80 drop-shadow-md">
                            <span class="text-2xl mr-2">📅</span>{{ formattedDate }}
                        </p>
                    </div>

                    <!-- Status Banner -->
                    <div
                        v-if="groupStatus === 'started'"
                        class="bg-emerald-500/20 backdrop-blur-md rounded-2xl p-4 mb-6 border border-emerald-400/30 text-center"
                    >
                        <span class="text-emerald-100 font-medium flex items-center justify-center gap-2">
                            <span class="text-xl">✅</span>
                            Ausgelost: Teilnehmer wurden benachrichtigt
                        </span>
                    </div>

                    <!-- Buddy Card -->
                    <div
                        v-if="buddy.name"
                        class="bg-gradient-to-r from-amber-500/20 to-orange-500/20 backdrop-blur-md rounded-2xl p-6 mb-6 border border-amber-400/30 text-center"
                    >
                        <p class="text-white/80 mb-2">Dein Wichtel wurde gezogen. Du darfst folgende Person beglücken:</p>
                        <p class="text-3xl font-bold text-white mb-3 drop-shadow-lg">{{ buddy.name }}</p>
                        <div class="bg-white/10 rounded-xl p-4 inline-block">
                            <span class="text-white/70 text-sm">Wunschzettel</span>
                            <p class="text-white font-medium">{{ buddy.wishlist || 'Kein Wunschzettel vorhanden' }}</p>
                        </div>
                    </div>

                    <!-- Main Glass Card -->
                    <div class="bg-white/10 backdrop-blur-md rounded-3xl border border-white/20 overflow-hidden shadow-2xl">
                        <!-- Participants Header -->
                        <div class="p-6 text-center border-b border-white/10">
                            <p class="text-2xl font-light text-white mb-3">Teilnehmer</p>
                            <div class="inline-flex items-center">
                                <span class="px-4 py-2 bg-sky-500/30 text-white text-sm rounded-l-full backdrop-blur-sm border border-sky-400/30">
                                    Anzahl
                                </span>
                                <span class="px-4 py-2 bg-white/20 text-white text-sm rounded-r-full backdrop-blur-sm border border-white/20 border-l-0">
                                    {{ totalMembers }}
                                </span>
                            </div>
                        </div>

                        <!-- Participants Table -->
                        <div class="p-6">
                            <div class="overflow-x-auto">
                                <table class="w-full">
                                    <thead>
                                        <tr class="border-b border-white/20">
                                            <th class="text-left py-3 px-4 font-medium text-white/70">Name</th>
                                            <th class="text-left py-3 px-4 font-medium text-white/70">Status</th>
                                            <th class="text-left py-3 px-4 font-medium text-white/70">Wunschzettel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="member in members"
                                            :key="member.id"
                                            :class="{ 'bg-emerald-500/30': isNew(member) }"
                                            class="border-b border-white/10 transition-colors duration-1000 hover:bg-white/5"
                                        >
                                            <td class="py-3 px-4 text-white">{{ member.name }}</td>
                                            <td class="py-3 px-4">
                                                <span
                                                    class="inline-block px-3 py-1 text-sm rounded-full backdrop-blur-sm"
                                                    :class="statusColor(member)"
                                                >
                                                    {{ status(member) }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 flex items-center gap-2">
                                                <span class="text-white">{{ wishlist(member) }}</span>
                                                <button
                                                    v-show="member.id === userId && groupStatus !== 'started'"
                                                    class="px-3 py-1 text-sm bg-white/10 hover:bg-white/20 text-white rounded-full backdrop-blur-sm border border-white/20 transition-all"
                                                    @click="toggleWishlistModal"
                                                >
                                                    Bearbeiten
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Add Member Form -->
                        <div v-show="groupStatus !== 'started' && isAdmin" class="p-6 border-t border-white/10">
                            <member-add-form
                                :group="group"
                                @newMember="addMember"
                            ></member-add-form>
                        </div>

                        <!-- Raffle Button -->
                        <div v-show="groupStatus !== 'started' && isAdmin" class="border-t border-white/10">
                            <button
                                class="w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold hover:from-amber-600 hover:to-orange-600 transition-all text-lg"
                                @click="startRaffle"
                            >
                                🎲 Auslosung Starten
                            </button>
                        </div>
                    </div>

                    <!-- Trust indicators -->
                    <div class="mt-8 flex flex-wrap justify-center gap-6 text-white/60 text-sm">
                        <div class="flex items-center gap-2">
                            <span>🔒</span>
                            <span>Daten werden sicher gespeichert</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span>🎄</span>
                            <span>Fröhliches Wichteln!</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Wishlist Modal -->
        <div v-show="showWishlist" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="toggleWishlistModal"></div>

                <div class="relative bg-white/95 backdrop-blur-lg rounded-3xl shadow-2xl max-w-lg w-full border border-white/50">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <span>📝</span>
                            Dein Wunschzettel
                        </h3>
                        <button
                            class="text-gray-400 hover:text-gray-600 transition-colors"
                            @click="toggleWishlistModal"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <div v-show="wishlistError" class="mb-4 p-4 bg-red-100 text-red-700 rounded-xl">
                            Es gab Probleme beim Speichern des Wunschzettels. Lade einmal die Seite neu und probier es bitte noch mal.
                        </div>
                        <textarea
                            v-model="user.wishlist"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500 resize-none"
                            rows="5"
                            placeholder="Schreibe hier deine Wünsche auf..."
                        ></textarea>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                        <button
                            class="px-5 py-2 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-colors"
                            @click="toggleWishlistModal"
                        >
                            Abbrechen
                        </button>
                        <button
                            class="px-5 py-2 bg-gradient-to-r from-sky-500 to-blue-500 text-white font-medium rounded-xl hover:from-sky-600 hover:to-blue-600 transition-all"
                            @click="saveWishlist"
                        >
                            Speichern
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                    return 'bg-amber-500/30 text-amber-100 border border-amber-400/30';
                case 'Nimmt teil':
                    return 'bg-emerald-500/30 text-emerald-100 border border-emerald-400/30';
                default:
                    return 'bg-white/20 text-white/80 border border-white/20';
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
