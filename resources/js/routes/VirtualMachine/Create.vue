<template>
    <div class="flex flex-wrap w-full">
        <div class="w-full mb-3 text-90 font-normal text-2xl">
            Create Virtual Machine
        </div>
        <div class="bg-white rounded-lg w-full shadow">
            <div class="flex flex-wrap border-b border-40 items-center">
                <div class="py-6 px-8 font-bold text-gray-500 w-1/5">VM Name</div>
                <div class="py-6 px-8 w-1/2">
                    <input v-model="form.name" id="name" type="text" placeholder="name" class="form-control w-full form-input form-input-bordered">
                </div>
            </div>
            <div class="flex flex-wrap border-b border-40 items-center">
                <div class="py-6 px-8 font-bold text-gray-500 w-1/5">Memory</div>
                <div class="py-6 px-8 w-1/2">
                    <input v-model="form.memory" id="memory" type="number" placeholder="512" class="form-control w-full form-input form-input-bordered">
                </div>
            </div>
            <div class="flex flex-wrap border-b border-40 items-center">
                <div class="py-6 px-8 font-bold text-gray-500 w-1/5">vCPU</div>
                <div class="py-6 px-8 w-1/2">
                    <input v-model="form.vcpu" id="vcpu" type="number" placeholder="1" class="form-control w-full form-input form-input-bordered">
                </div>
            </div>
            <div class="w-full px-4 pt-4 ml-4 font-bold text-gray-500">
                Select your ISO
            </div>
            <div class="flex flex-wrap border-40 mx-8 my-4">
                <div class="w-full">
                    <div class="w-4/5 border-40 border-2">
                        <div class="w-full py-2 px-4 border-b-2">
                            <span v-if="!selectedIso">_</span>
                            <span v-else>{{ selectedIso.path }}</span>
                        </div>
                        <div class="p-4">
                            <div v-for="file in files">
                                <button type="button" class="underline" @click.prevent="selectNewPath(file)">
                                    {{ file.path }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-8 pt-4 font-bold text-gray-500 border-t">
                Select your Network
            </div>
            <div class="flex flex-wrap border-40 mx-8 my-4">
                <div class="w-full">
                    <div class="w-4/5 border-40 border-2">
                        <div class="w-full py-2 px-4 border-b-2">
                            <span v-if="!selectedNetwork">_</span>
                            <span v-else>{{ selectedNetwork.name }}</span>
                        </div>
                        <div class="p-4" v-if="networks.length > 0">
                            <div v-for="network in networks">
                                <button type="button" class="underline" @click.prevent="selectNetwork(network)">
                                    {{ network.name }} -
                                    {{ network.ip }}
                                </button>
                            </div>
                        </div>
                        <div v-else class="p-4 italic">
                            No available networks...
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full flex flex-wrap justify-end">
                <button @click.prevent="create" type="button" class="py-2 px-4 rounded bg-blue-500 m-4 text-white">
                    Create VM
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: {
                    name: '',
                    memory: '',
                    vcpu: '',
                    disk: {
                        // This is where our VMs are stored.
                        path: '/var/lib/libvirt/images/',
                        driver: 'qcow',
                        // Device to be presented to guest OS eg. 'hda'
                        dev: 'hda1',
                        size: '15G',
                    },
                    networks: {
                        mac: '',
                        network: 'default',
                    },
                },
                selectedIso: null,
                selectedNetwork: null,
            }
        },
        computed: {
            files() {
                return this.$store.state.homestead.files;
            },
            networks() {
                return this.$store.state.homestead.networks || [];
            },
            path() {
                return this.$store.state.homestead.path
            },
        },
        methods: {
            selectNetwork(network) {
                this.selectedNetwork = network;
            },
            selectNewPath(file) {
                this.selectedIso = file;
            },
            async create() {
                await this.$store.dispatch(Nova.E.CREATE_VM, {
                    form: this.form,
                    selectedIso: this.selectedIso,
                    selectedNetwork: this.selectedNetwork
                }, { root: true })

                setTimeout(() => Nova.app.$router.push({
                    name: 'homestead.index',
                    params: this.$route.params
                }), 200)
            }
        },
        async mounted() {
            await this.$store.dispatch(Nova.E.GET_PATH, this.$route, { root: true });
            await this.$store.dispatch(Nova.E.FETCH_NETWORKS, this.$route, { root: true});
        }
    }
</script>
