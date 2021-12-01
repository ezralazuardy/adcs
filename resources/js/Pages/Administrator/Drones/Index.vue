<template>
    <app-layout title="Drones">
        <div v-if="drones.length === 0">
            <div class="px-4 py-5 bg-white sm:p-6 shadow rounded-lg text-center font-medium">
                Belum ada drone yang terdeteksi.
            </div>
        </div>
        <div v-else>
            <div class="lg:px-14 flex flex-wrap items-center justify-start">
                <div v-for="drone in drones"
                     class="flex-shrink-0 m-6 relative overflow-hidden bg-pink-600 rounded-lg max-w-xs shadow-lg">
                    <div class="flex justify-end">
                        <div class="w-8 mt-4 mr-8">
                            <div class="shadow w-full rounded border-2 border-gray-300 flex my-1 relative">
                                <div
                                    class="border-r-4 h-2 rounded-r absolute flex border-gray-300 ml-8 mt-1 z-10"></div>
                                <div
                                    class="cursor-default bg-green-300 text-xs font-bold leading-none flex items-center justify-center m-1 py-1 text-center text-white w-full"></div>
                            </div>
                        </div>
                    </div>
                    <svg class="absolute bottom-0 left-0 mb-8" viewBox="0 0 375 283" fill="none"
                         style="transform: scale(1.5); opacity: 0.1;">
                        <rect x="159.52" y="175" width="152" height="152" rx="8" transform="rotate(-45 159.52 175)"
                              fill="white"/>
                        <rect y="107.48" width="152" height="152" rx="8" transform="rotate(-45 0 107.48)" fill="white"/>
                    </svg>
                    <div class="relative pt-4 px-10 flex items-center justify-center">
                        <div class="block absolute w-48 h-48 bottom-0 left-0 -mb-24 ml-16"
                             style="background: radial-gradient(black, transparent 60%); transform: rotate3d(0, 0, 1, 20deg) scale3d(1, 0.6, 1); opacity: 0.2;"></div>
                        <img class="relative w-60 max-h-28" :src="`/img/drones/${drone.type}.png`"
                             alt="{{ drone.name }}">
                    </div>
                    <div class="relative text-white px-6 pb-6 mt-6">
                        <span class="block opacity-75 -mb-1">Drone</span>
                        <div class="flex justify-between">
                            <span class="block font-semibold text-xl font-mono pt-1">{{ drone.name }}</span>
                            <span
                                @click="confirmUpdate(drone)"
                                class="bg-white hover:bg-gray-200 hover:cursor-pointer rounded-full text-pink-500 text-md font-bold px-4 py-2 leading-none flex items-center">Kelola</span>
                        </div>
                    </div>
                </div>
            </div>
            <jet-modal :show="confirmingUpdate" @close="closeUpdateModal" title="Kelola Drone">
                <template #content>
                    Sesuaikan data atribut drone yang ingin diubah.
                    <jet-validation-errors class="mt-4"/>
                    <div class="mt-4">
                        <jet-label for="drone_id" value="ID Drone"/>
                        <jet-input id="drone_id" type="text"
                                   class="block w-full mt-1 bg-gray-100 cursor-not-allowed normal-case"
                                   placeholder="ID drone" ref="updateId" v-model="this.updateForm.target_id" disabled/>
                    </div>
                    <div class="mt-4">
                        <jet-label for="drone_type" value="Tipe Drone"/>
                        <jet-input id="drone_type" type="text"
                                   class="block w-full mt-1 bg-gray-100 cursor-not-allowed normal-case"
                                   placeholder="Tipe drone" ref="updateType" v-model="this.updateForm.target_type"
                                   disabled/>
                    </div>
                    <div class="mt-4">
                        <jet-label for="status" value="Status"/>
                        <jet-input id="status" type="text"
                                   class="block w-full mt-1 bg-gray-100 cursor-not-allowed normal-case text-green-500 font-bold"
                                   placeholder="Status drone" ref="updateStatus" value="Online" disabled/>
                    </div>
                    <div class="mt-4">
                        <jet-label for="battery" value="Baterai"/>
                        <jet-input id="battery" type="text"
                                   class="block w-full mt-1 bg-gray-100 cursor-not-allowed normal-case"
                                   placeholder="Baterai drone" ref="updateBattery" v-model="batteryLevel" disabled/>
                    </div>
                    <div class="mt-4">
                        <jet-label for="latitude" value="Latitude"/>
                        <jet-input id="latitude" type="number" step="any" class="block w-full mt-1"
                                   placeholder="Masukkan koordinat latitude"
                                   ref="updateLat" v-model="updateForm.lat"/>
                    </div>
                    <div class="mt-4">
                        <jet-label for="longitude" value="Longitude"/>
                        <jet-input id="longitude" type="number" step="any" class="block w-full mt-1"
                                   placeholder="Masukkan koordinat longitude"
                                   ref="updateLon" v-model="updateForm.lon" @keyup.enter="update"/>
                    </div>
                </template>
                <template #buttons>
                    <jet-button type="button" @click="update"
                                :class="{ 'opacity-25': updateForm.processing }"
                                :disabled="updateForm.processing"
                                class="inline-flex justify-center w-full px-4 py-2 mt-2 sm:ml-3 sm:w-auto">
                        Ubah Lokasi
                    </jet-button>
                    <jet-secondary-button @click="closeUpdateModal"
                                          class="inline-flex justify-center w-full px-4 py-2 mt-2 sm:ml-3 sm:w-auto">
                        Batalkan
                    </jet-secondary-button>
                </template>
            </jet-modal>
            <jet-success-notification
                :show="showingSuccessNotification"
                :title="successNotification.title"
                :description="successNotification.description"
                @close="closeSuccessNotification"/>
            <jet-danger-notification
                :show="showingDangerNotification"
                :title="dangerNotification.title"
                :description="dangerNotification.description"
                @close="closeDangerNotification"/>
        </div>
    </app-layout>
</template>

<script>
import {defineComponent} from 'vue'
import {useForm} from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout'
import JetModal from '@/Jetstream/Modal'
import JetValidationErrors from '@/Jetstream/ValidationErrors'
import JetButton from '@/Jetstream/Button'
import JetSecondaryButton from '@/Jetstream/SecondaryButton'
import JetInput from '@/Jetstream/Input'
import JetLabel from '@/Jetstream/Label'
import JetSuccessNotification from '@/Jetstream/SuccessNotification'
import JetDangerNotification from '@/Jetstream/DangerNotification'

export default defineComponent({
    props: {
        _drones: Object
    },
    components: {
        AppLayout,
        JetModal,
        JetValidationErrors,
        JetButton,
        JetSecondaryButton,
        JetInput,
        JetLabel,
        JetSuccessNotification,
        JetDangerNotification
    },
    data() {
        return {
            drones: this._drones,
            confirmingUpdate: false,
            showingSuccessNotification: false,
            showingDangerNotification: false,
            updateForm: useForm({
                target_id: null,
                target_type: null,
                battery: null,
                lat: null,
                lon: null
            }),
            successNotification: {
                title: null,
                description: null
            },
            dangerNotification: {
                title: null,
                description: null
            }
        }
    },
    computed: {
        batteryLevel: {
            get: function () {
                return `${this.updateForm.battery ?? 0}%`;
            },
            set: function (newValue) {
                this.updateForm.battery = newValue;
            }
        }
    },
    mounted() {
        Echo.private('drones').listen('DroneDataUpdated', res => {
            this.drones = res['data']
        })
        setInterval(() => {
            axios.get(route('api.drones.broadcast'))
        }, 4000)
    },
    methods: {
        update() {
            this.updateForm.post(route('drones.store'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeUpdateModal()
                    this.showSuccessNotification('Data drone berhasil diubah', 'Sistem telah berhasil mengubah data drone')
                },
                onError: () => this.showDangerNotification('Kesalahan telah terjadi', 'Sistem tidak dapat mengubah data drone, mohon periksa ulang form')
            })
        },
        confirmUpdate(drone) {
            this.updateForm.target_id = drone.name
            this.updateForm.target_type = drone.type
            this.updateForm.battery = drone.battery
            this.updateForm.lat = drone.lat
            this.updateForm.lon = drone.lon
            setTimeout(() => this.confirmingUpdate = true, 150)
            setTimeout(() => this.$refs.updateLat.focus(), 300)
        },
        closeUpdateModal() {
            this.confirmingUpdate = false
            setTimeout(() => {
                this.clearErrors()
                this.updateForm.clearErrors()
                this.updateForm.reset()
                this.updateForm.target_id = null
                this.updateForm.target_type = null
                this.updateForm.battery = null
                this.updateForm.lat = null
                this.updateForm.lon = null
            }, 500)
        },
        showSuccessNotification(title, description) {
            this.successNotification.title = title
            this.successNotification.description = description
            this.showingSuccessNotification = true
            setTimeout(() => this.showingSuccessNotification ? this.closeSuccessNotification() : null, 5000)
        },
        showDangerNotification(title, description) {
            this.dangerNotification.title = title
            this.dangerNotification.description = description
            this.showingDangerNotification = true
            setTimeout(() => this.showingDangerNotification ? this.closeDangerNotification() : null, 5000)
        },
        closeSuccessNotification() {
            this.showingSuccessNotification = false
        },
        closeDangerNotification() {
            this.showingDangerNotification = false
        },
        clearErrors() {
            this.$page.props.errors = []
        }
    }
})
</script>
