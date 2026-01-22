<template>
    <div class="space-y-4">
        <h2 class="text-lg font-medium text-white">Teilnehmer einladen</h2>

        <div>
            <label class="block text-sm font-medium text-white/80 mb-1">Name</label>
            <input
                v-model="name"
                :class="{ 'border-red-400': hasErrors('name') }"
                class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                type="text"
                placeholder="Name"
            >
            <p v-show="hasErrors('name')" class="mt-1 text-sm text-red-300">{{ errorMessage('name') }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-white/80 mb-1">E-Mail</label>
            <input
                v-model="email"
                :class="{ 'border-red-400': hasErrors('email') }"
                class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/50 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                type="email"
                placeholder="E-Mail"
            >
            <p v-show="hasErrors('email')" class="mt-1 text-sm text-red-300">{{ errorMessage('email') }}</p>
        </div>

        <button
            class="px-5 py-2 bg-gradient-to-r from-sky-500 to-blue-500 text-white font-medium rounded-xl hover:from-sky-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-offset-2 focus:ring-offset-transparent transition-all"
            @click="submit"
        >
            Teilnehmer Einladen
        </button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "MemberAddForm",
    props: ['group'],
    data() {
        return {
            name: '',
            email: '',
            errors: {},
        };
    },
    computed: {
        groupId() {
            return this.group.id;
        },
        token() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('token');
        }
    },
    methods: {
        submit() {
            axios.post(`/api/v1/wichtelgroups/${this.groupId}/wichtelmembers`, {
                name: this.name,
                email: this.email,
            }, {
                headers: {
                    Authorization: `Bearer ${this.token}`
                }
            }).then((response) => {
                this.name = '';
                this.email = '';
                this.errors = {};
                this.$emit('newMember', response.data);
            }).catch((error) => {
                if (error.response) {
                    if (error.response.status === 422) {
                        this.errors = error.response.data.errors;
                    }
                }
            });
        },
        hasErrors(field) {
            return this.errors[field] !== undefined;
        },
        errorMessage(field) {
            return this.hasErrors(field) ? this.errors[field][0] : '';
        }
    }
};
</script>
