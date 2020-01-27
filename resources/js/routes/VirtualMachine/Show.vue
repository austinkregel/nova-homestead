<template>
    <div class="container mx-auto">
        <div class="bg-white shadow p-4" v-if="host">
            <div class="w-full flex flex-wrap">
                <img :src="'/images/'+host.osIcon" alt="" class="w-12 h-12">
                <div class="flex flex-wrap items-center flex-1 ml-4 justify-between">
                    <div class="flex flex-col ml-2">
                        <div class="text-2xl font-bold">
                            {{ host.name }}
                        </div>
                        <div class="text-sm">
                            {{ host.memoryReadable }} GB Memory
                            <span v-if="host.disks.length > 0">/ {{ host.disks.map(disk => disk.disk_size_readable).join(' ') }} GB Disk</span>
                            /
                            {{ host.vcpu }} vCPU cores
                            <span class="text-80" v-if="host.osName">- {{ host.osName }}</span>
                        </div>
                    </div>
                    <div class="">
                        <div class="toggle-switch">
                            <button @click="toggleVmStatus" class="flex flex-col justify-between">
                            <span v-if="!shouldBeOn" class="border rounded-full border-gray-400 flex items-center cursor-pointer w-12 justify-start">
                                <span class="rounded-full border w-6 h-6 border-gray-400 shadow-inner bg-white shadow"></span>
                            </span>
                                <span v-if="shouldBeOn" class="border rounded-full border-gray-400 flex items-center cursor-pointer w-12 bg-green-500 justify-end">
                                <span class="rounded-full border w-6 h-6 border-gray-400 shadow-inner bg-white shadow"></span>
                            </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="w-full flex justify-between items-center border-t-2 border-b-2 border-gray-300 mt-4 py-2">
                <div>
                    ipv4: {{ host.networks.filter(network => network.private).map(network => network.ip).join(', ') }}
                </div>
                <a class="flex items-center" href="http://supervisor.test:6080/vnc.html?host=127.0.0.1&port=6080" target="_blank">
                    Console <svg class="ml-1 w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2 2c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v18l-8-4-8 4V2zm2 0v15l6-3 6 3V2H4z"/></svg>
                </a>
            </div>
            <div class="w-full flex flex-wrap mt-6">
                <div class="w-1/5 flex flex-col">
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.access', params: $router.params }">Access</router-link>
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.destroy', params: $router.params }">Destroy</router-link>
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.devices', params: $router.params }">Devices</router-link>
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.power', params: $router.params }">Power</router-link>
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.resize', params: $router.params }">Resize</router-link>
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.snapshots', params: $router.params }">Snapshots</router-link>
                    <router-link class="py-1 text-base text-thin text-gray-500" :to="{name: 'supervisor.show.tags', params: $router.params }">Tags</router-link>
                </div>
                <div class="w-4/5">
                    <router-view></router-view>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: [],
        methods: {
            async toggleVmStatus() {
                await this.$store.dispatch(Nova.E.TOGGLE_VM, this.host, { root: true});
            },
        },
        computed: {
            shouldBeOn() {
                return this.host.shouldBeOn;
            },
            host() {
                return this.$store.getters['supervisor/host'];
            },
            hypervisor() {
                return this.$store.state.supervisor.hypervisor;
            },
            hosts() {
                return this.$store.getters['supervisor/hosts'];
            }
        },
        mounted() {
        }
    }
</script>

