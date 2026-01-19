<template>
    <div>
        <div v-show="success" class="py-6">
            <p class="text-gray-700">Wir haben dir eine Mail geschickt mit mehr Informationen, wie es weiter geht.</p>
        </div>

        <div v-show="!isSubmitted" class="py-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name der Wichtelgruppe</label>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aktionstag</label>
                    <input
                        v-model="date"
                        :min="minDate"
                        :class="{ 'border-red-500': hasErrors('date') }"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        type="date"
                    >
                    <p v-show="hasErrors('date')" class="mt-1 text-sm text-red-600">{{ errorMessage('date') }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dein Name</label>
                    <input
                        v-model="username"
                        :class="{ 'border-red-500': hasErrors('username') }"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        type="text"
                        placeholder="Name"
                    >
                    <p v-show="hasErrors('username')" class="mt-1 text-sm text-red-600">{{ errorMessage('username') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Deine E-Mail</label>
                    <input
                        v-model="email"
                        :class="{ 'border-red-500': hasErrors('email') }"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        type="email"
                        placeholder="E-Mail"
                    >
                    <p v-show="hasErrors('email')" class="mt-1 text-sm text-red-600">{{ errorMessage('email') }}</p>
                </div>
            </div>
        </div>

        <button
            v-show="!isSubmitted"
            class="px-6 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
            @click="submit"
            :disabled="isSubmitted"
        >
            Neue Wichtel Aktion starten
        </button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: "CreateWichtelGroupForm",
    data() {
        return {
            name: '',
            date: '',
            username: '',
            email: '',
            errors: {},
            isSubmitted: false,
            success: false
        };
    },
    computed: {
        minDate() {
            return new Date().toISOString().split('T')[0];
        }
    },
    methods: {
        submit() {
            this.isSubmitted = true;
            this.errors = {};
            axios.post('/api/v1/wichtelgroups', {
                name: this.name,
                date: this.date,
                username: this.username,
                email: this.email,
            }).then(() => {
                this.success = true;
            }).catch((error) => {
                this.isSubmitted = false;
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
