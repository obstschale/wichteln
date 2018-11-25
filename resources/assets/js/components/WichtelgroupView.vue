<template>
    <div>
        <div class="top-bar"></div>
        <section id="top" class="hero is-info is-medium is-bold">
            <div class="hero-body">
                <div class="container has-text-centered">
                    <h1 class="title has-text-black">{{ group.name }}</h1>
                    <h2 class="subtitle has-text-black">
                        am {{ date }}
                    </h2>
                </div>
            </div>
        </section>

        <div class="container">
            <!-- START ARTICLE FEED -->
            <section class="articles">
                <div class="column is-8 is-offset-2">
                    <!-- START ARTICLE -->
                    <div class="card article">
                        <div v-show="groupStatus === 'started'" class="card-header">
                            <div class="card-header-title has-text-white">
                                Ausgelost: Teilnehmer wurden benachrichtigt
                            </div>
                        </div>
                        <div class="card-content">
                            <div class="has-text-centered">
                                <p class="title article-title">Teilnehmer</p>
                                <div class="tags has-addons level-item">
                                    <span class="tag is-rounded is-info">Anzahl</span>
                                    <span class="tag is-rounded">{{ totalMembers }}</span>
                                </div>
                            </div>
                            <div class="content">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th><abbr title="Name">Name</abbr></th>
                                        <th><abbr title="Teilnahme-Status">Status</abbr></th>
                                        <th><abbr title="Wunschzettel ausgefüllt">Wunschzettel</abbr></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="member in members" :class="{ 'is-selected': isNew(member) }">
                                            <td>{{ member.name }}</td>
                                            <td><a class="button" :class="statusColor(member)">{{ status(member) }}</a></td>
                                            <td>
                                                <span>{{ wishlist(member) }}</span>
                                                <span class="button" v-show="member.id === userId && groupStatus !== 'started'" @click="toggleWishlistModal">
                                                    Wunschzettel bearbeiten
                                                </span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <hr>

                                <member-add-form
                                        v-show="groupStatus !== 'started' && isAdmin"
                                        :group="group"
                                        @newMember="addMember"
                                ></member-add-form>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button v-show="groupStatus !== 'started' && isAdmin" class="button is-warning" @click="startRaffle">Auslosung
                                Starten
                            </button>
                        </div>
                    </div>
                    <!-- END ARTICLE -->
                </div>
            </section>
        </div>

        <div class="modal" :class="{ 'is-active': showWishlist }">
            <div class="modal-background" @click="toggleWishlistModal"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Dein Wunschzettel</p>
                    <button class="delete" aria-label="close" @click="toggleWishlistModal"></button>
                </header>
                <section class="modal-card-body">
                    <div class="notification is-danger" v-show="wishlistError">
                        Es gab Probleme beim speichern des Wunschzettel. Lade einmal die Seite neu und probier es bitte noch mal.
                    </div>
                    <div class="field">
                        <div class="control">
                            <textarea v-model="user.wishlist" class="textarea is-success" placeholder="Wunschzettel schreiben"></textarea>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" @click="saveWishlist">Speichern</button>
                </footer>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="toggleWishlistModal"></button>
        </div>
    </div>
</template>

<script>
    export default {
        name: "WichtelgroupView",
        props: ['userId', 'group', 'isAdmin'],
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
            }
        },
        mounted() {
            this.members = this.group.users;
            this.groupStatus = this.group.status;
            this.user.wishlist = this.members.find((member) => {
                return member.id === this.userId;
            }).pivot.wishlist;
        },
        computed: {
            date() {
                moment.locale('de');
                return moment(this.group.date).calendar();
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
                        this.newMember= {};
                    }, 5000);
                }, 100);
            },
            isNew(member) {
              return this.newMember.id === member.id;
            },
            status(member) {
                if (! member.pivot) {
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
                        return 'is-warning';
                    case 'Nimmt teil':
                        return 'is-success';
                    default:
                        return '';
                }
            },
            wishlist(member) {
                if (! member.pivot) {
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
                        headers: {Authorization: `Bearer ${this.token}`}
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
                    headers: {Authorization: `Bearer ${this.token}`}
                }).then(() => {
                    this.toggleWishlistModal();
                }).catch(() => {
                    this.wishlistError = true;
                })
            }
        }
    }
</script>

<style scoped>
    .top-bar {
        background-color: darkgreen;
        height: 7px;
    }

    #top {
        background-color: #ffffff;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23d42426' fill-opacity='.1'%3E%3Cpath fill-rule='evenodd' d='M11 0l5 20H6l5-20zm42 31a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM0 72h40v4H0v-4zm0-8h31v4H0v-4zm20-16h20v4H20v-4zM0 56h40v4H0v-4zm63-25a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM53 41a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-30 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-28-8a5 5 0 0 0-10 0h10zm10 0a5 5 0 0 1-10 0h10zM56 5a5 5 0 0 0-10 0h10zm10 0a5 5 0 0 1-10 0h10zm-3 46a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm10 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM21 0l5 20H16l5-20zm43 64v-4h-4v4h-4v4h4v4h4v-4h4v-4h-4zM36 13h4v4h-4v-4zm4 4h4v4h-4v-4zm-4 4h4v4h-4v-4zm8-8h4v4h-4v-4z'/%3E%3C/g%3E%3C/svg%3E");
    }

    #top .container {
        margin-top: -100px;
    }

    .articles {
        margin: 5rem 0;
        margin-top: -142px;
    }
    .articles .content p {
        line-height: 1.9;
        margin: 15px 0;
    }
    div.column.is-8:first-child {
        padding-top: 0;
        margin-top: 0;
    }
    .article-title {
        font-size: 2rem;
        font-weight: lighter;
        line-height: 2;
    }
    .table tr.is-selected {
        background-color: darkgreen;
    }
    .table tr {
        transition: background-color 1000ms linear;
    }
    .card-header {
        background-color: darkgreen;
    }
    .card-footer button {
        width: 100%;
    }
</style>
