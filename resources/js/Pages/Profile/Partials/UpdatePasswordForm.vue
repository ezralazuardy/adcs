<template>
    <jet-form-section @submitted="updatePassword">
        <template #title>
            Perbarui Kata Sandi
        </template>
        <template #description>
            Pastikan akun Anda menggunakan kata sandi acak yang panjang agar tetap aman.
        </template>
        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="current_password" value="Kata Sandi Saat Ini"/>
                <jet-input id="current_password" type="password" class="mt-1 block w-full normal-case"
                           v-model="form.current_password" ref="current_password" autocomplete="current-password"/>
                <jet-input-error :message="form.errors.current_password" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password" value="Kata Sandi Baru"/>
                <jet-input id="password" type="password" class="mt-1 block w-full normal-case" v-model="form.password"
                           ref="password" autocomplete="new-password"/>
                <jet-input-error :message="form.errors.password" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="password_confirmation" value="Konfirmasi Kata Sandi Baru"/>
                <jet-input id="password_confirmation" type="password" class="mt-1 block w-full normal-case"
                           v-model="form.password_confirmation" autocomplete="new-password"/>
                <jet-input-error :message="form.errors.password_confirmation" class="mt-2"/>
            </div>
        </template>
        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Kata Sandi berhasil diperbarui.
            </jet-action-message>
            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Simpan
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import {defineComponent} from 'vue'
import JetActionMessage from '@/Jetstream/ActionMessage'
import JetButton from '@/Jetstream/Button'
import JetFormSection from '@/Jetstream/FormSection'
import JetInput from '@/Jetstream/Input'
import JetInputError from '@/Jetstream/InputError'
import JetLabel from '@/Jetstream/Label'

export default defineComponent({
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel
    },
    data() {
        return {
            form: this.$inertia.form({
                current_password: null,
                password: null,
                password_confirmation: null
            })
        }
    },
    methods: {
        updatePassword() {
            this.form.put(route('user-password.update'), {
                errorBag: 'updatePassword',
                preserveScroll: true,
                onSuccess: () => this.form.reset(),
                onError: () => {
                    if (this.form.errors.password) {
                        this.form.reset('password', 'password_confirmation')
                        this.$refs.password.focus()
                    }
                    if (this.form.errors.current_password) {
                        this.form.reset('current_password')
                        this.$refs.current_password.focus()
                    }
                }
            })
        }
    }
})
</script>
