<template>
    <div class="shadow rounded bg-white p-4 my-4">
        <div class="flex flex-wrap w-full items-center" v-if="hypervisor">
            <img :src="'/images/'+host.osIcon" alt="" class="w-12">
            <div class="flex flex-wrap items-center flex-1 ml-4 justify-between">
                <div class="flex flex-col">
                    <div class="text-xl font-bold">
                        <router-link class="text-blue-600 underline" :to="{ name: 'homestead.show', params: { hypervisor: hypervisor.id, host: host.uuid } }">{{ host.name }}</router-link>
                    </div>
                    <div class="text-sm">
                        {{ host.memoryReadable }} GB Memory
                        <span v-if="host.disks.length > 0">/ {{ host.disks.map(disk => disk.disk_size_readable).join(' ') }} GB Disk</span>
                        /
                        {{ host.vcpu }} vCPU cores
                        <span class="text-80" v-if="host.osName">- {{ host.osName }}</span>
                    </div>
                </div>
                <div>
                    {{ host.networks.filter(network => network.private).map(network => network.ip).join(', ') }}
                </div>
                <div class="relative">
                    <button class="py-2 px-4 flex items-center text-blue-600" @click.prevent="displayModal = !displayModal">
                        More <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
                    </button>

                    <div class="absolute flex flex-col bg-gray-100 w-48 right-0 shadow p-4" v-if="displayModal">
                        <div class="mr-4">
                            <button @click.prevent="forceStop" class="text-red-500 rounded py-1 px-4 text-red-100">
                                Force Stop VM
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>Nothing yet...</div>
    </div>
</template>

<script>
    export default {
        props: ['host'],
        data() {
            return {
                toggleBusy: false,
                displayModal: false,
            }
        },
        computed: {
            hypervisor() {
                return this.$store.state.homestead.hypervisor;
            }
        },
        methods: {
            forceStop() {
                this.$store.dispatch(Nova.E.FORCE_STOP, {}, { root: true });
            }
        }
    }
</script>
