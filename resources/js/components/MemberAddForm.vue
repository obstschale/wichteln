<template>
    <div class="space-y-4">
        <h2 class="text-lg font-medium text-gray-900">Teilnehmer einladen</h2>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
            <input
                v-model="name"
                :class="{ 'border-red-500': hasErrors('name') }"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                type="text"
                placeholder="Name"
            >
            <p v-show="hasErrors('name')" class="mt-1 text-sm text-red-600">{{ errorMessage('name') }}</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">E-Mail</label>
            <input
                v-model="email"
                :class="{ 'border-red-500': hasErrors('email') }"
                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                type="email"
                placeholder="E-Mail"
            >
            <p v-show="hasErrors('email')" class="mt-1 text-sm text-red-600">{{ errorMessage('email') }}</p>
        </div>

        <button
            class="px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
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
