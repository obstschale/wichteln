<template>
    <div>
        <h1>{{ group.name }} - ({{ date }})</h1>
        <ul>
            <li v-for="member in members">{{ member.name }} - ({{ status(member) }})</li>
        </ul>

        <member-add-form
            v-show="group.status !== 'started'"
            :group="group"
            @newMember="addMember"
        ></member-add-form>

        <button v-show="group.status !== 'started'" class="button" @click="startRaffle">Auslosung Starten</button>
    </div>
</template>

<script>
    export default {
        name: "WichtelgroupView",
        props: ['group'],
        data() {
            return {
                members: []
            }
        },
        mounted() {
           this.members = this.group.users;
        },
        computed: {
            date() {
                moment.locale('de');
                return moment(this.group.date).calendar();
            },
            token() {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get('token');
            }
        },
        methods: {
            addMember($member) {
                console.log($member);
                this.members.push($member);
            },
            status(member) {
                return member.pivot ? member.pivot.status : 'invited';
            },
            startRaffle() {
                axios.put(`/api/v1/wichtelgroups/${this.group.id}`, {
                    name: this.group.name,
                    date: this.group.date,
                    status: 'started'
                }, {
                    headers: {Authorization: `Bearer ${this.token}`}
                });
            }
        }
    }
</script>

<style scoped>

</style>
