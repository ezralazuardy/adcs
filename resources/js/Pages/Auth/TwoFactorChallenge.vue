<template>
    <Head title="Two-factor Confirmation"/>

    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo/>
        </template>

        <div class="mb-4 text-sm text-gray-600">
            <template v-if="! recovery">
                Harap konfirmasikan akses ke akun Anda dengan memasukkan kode otentikasi yang diberikan oleh
                aplikasi autentikator Anda.
            </template>

            <template v-else>
                Harap konfirmasikan akses ke akun Anda dengan memasukkan salah satu kode pemulihan darurat Anda.
            </template>
        </div>

        <jet-validation-errors class="mb-4"/>

        <form @submit.prevent="submit">
            <div v-if="! recovery">
                <jet-label for="code" value="Kode Otentikasi"/>
                <jet-input ref="code" id="code" type="text" inputmode="numeric" class="mt-1 block w-full"
                           v-model="form.code" autofocus autocomplete="one-time-code"/>
            </div>

            <div v-else>
                <jet-label for="recovery_code" value="Kode Pemulihan"/>
                <jet-input ref="recovery_code" id="recovery_code" type="text" class="mt-1 block w-full"
                           v-model="form.recovery_code" autocomplete="one-time-code"/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        @click.prevent="toggleRecovery">
                    <template v-if="! recovery">
                        Gunakan kode pemulihan
                    </template>

                    <template v-else>
                        Gunakan kode otentikasi
                    </template>
                </button>

                <jet-button class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Masuk
                </jet-button>
            </div>
        </form>
    </jet-authentication-card>
</template>

<script>
import {defineComponent} from 'vue'
import {Head} from '@inertiajs/inertia-vue3'
import JetAuthenticationCard from '@/Jetstream/AuthenticationCard'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'
import JetButton from '@/Jetstream/Button'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetValidationErrors from '@/Jetstream/ValidationErrors'

export default defineComponent({
    components: {
        Head,
        JetAuthenticationCard,
        JetAuthenticationCardLogo,
        JetButton,
        JetInput,
        JetLabel,
        JetValidationErrors
    },
    data() {
        return {
            recovery: false,
            form: this.$inertia.form({
                code: null,
                recovery_code: null
            })
        }
    },
    methods: {
        toggleRecovery() {
            this.recovery ^= true
            this.$nextTick(() => {
                if (this.recovery) {
                    this.$refs.recovery_code.focus()
                    this.form.code = null
                } else {
                    this.$refs.code.focus()
                    this.form.recovery_code = null
                }
            })
        },
        submit() {
            this.form.post(this.route('two-factor.login'))
        }
    }
})
</script>
