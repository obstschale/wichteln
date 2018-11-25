<template>
    <div>
        <div v-show="success" class="section">
            <p>Wir haben dir eine Mail geschickt mit mehr Informationen, wie es weiter geht.</p>
        </div>

        <div v-show="! isSubmitted" class="section">
            <div class="field">
                <label class="label">Name der Wichtelgruppe</label>
                <div class="control">
                    <input v-model="name" :class="{'is-danger' : hasErrors('name')}" class="input" type="text" placeholder="Name">
                </div>
                <p v-show="hasErrors('name')" class="help is-danger">{{ errorMessage('name') }}</p>
            </div>

            <div class="field">
                <label class="label">Aktionstag</label>
                <div class="control">
                    <input v-model="date" :class="{'is-danger' : hasErrors('date')}" class="input" type="date" placeholder="Name">
                </div>
                <p v-show="hasErrors('date')" class="help is-danger">{{ errorMessage('date') }}</p>
            </div>

            <div class="field">
                <label class="label">Dein Name</label>
                <div class="control">
                    <input v-model="username" :class="{'is-danger' : hasErrors('username')}" class="input" type="text" placeholder="Name">
                </div>
                <p v-show="hasErrors('username')" class="help is-danger">{{ errorMessage('username') }}</p>
            </div>

            <div class="field">
                <label class="label">Deine E-Mail</label>
                <div class="control">
                    <input v-model="email" :class="{'is-danger' : hasErrors('email')}" class="input" type="email" placeholder="E-Mail" value="hello@">
                </div>
                <p v-show="hasErrors('email')" class="help is-danger">{{ errorMessage('email') }}</p>
            </div>

            <div class="field is-grouped">
                <div class="control">
                    <button class="button is-link" @click="submit" :disabled="isSubmitted">Neue Wichtel Aktion starten</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
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
                }).then((data) => {
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
    }
</script>

<style scoped>

</style>
