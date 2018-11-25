<template>
    <div>
        <div class="field">
            <label class="label">Name</label>
            <div class="control">
                <input v-model="name" :class="{'is-danger' : hasErrors('name')}" class="input" type="text" placeholder="Name">
            </div>
            <p v-show="hasErrors('name')" class="help is-danger">{{ errorMessage('name') }}</p>
        </div>

        <div class="field">
            <label class="label">E-Mail</label>
            <div class="control">
                <input v-model="email" :class="{'is-danger' : hasErrors('email')}" class="input" type="email" placeholder="Name">
            </div>
            <p v-show="hasErrors('email')" class="help is-danger">{{ errorMessage('email') }}</p>
        </div>

        <div class="field is-grouped">
            <div class="control">
                <button class="button is-link" @click="submit">Teilnehmer Einladen</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "MemberAddForm",
        props: ['group'],
        data() {
            return {
                name: '',
                email: '',
                errors: {},
            }
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
                }).then((data) => {
                    this.name = '';
                    this.email = '';
                    this.errors = {};
                    this.$emit('newMember', data.data)
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
