<template>
    <div class="flex flex-wrap w-full" v-if="hosts">
        <div class="w-full mb-4 flex justify-between items-center">
            <div class="font-bold text-2xl">
                Virtual Machines
            </div>
            <router-link :to="{name: 'supervisor.create', params: { hypervisor: $route.params.hypervisor } }" class="bg-blue-500 font-bold px-4 py-2 rounded text-white">
                Create Machine
            </router-link>
        </div>

        <div v-if="shouldShowEdit" class="w-full underline bg-red-100 text-red-700 border border-red-700 p-4 rounded flex flex-wrap">
            <router-link :to="{name: 'supervisor.edit', params: { hypervisor: hypervisor.id } }">Please edit this hypervisor, we cannot connect...</router-link>
        </div>
        <div class="flex flex-wrap w-full" v-if="hosts.length > 0">
            <div v-for="host in hosts" :key="host.id" class="w-full">
                <host :host="host"></host>
            </div>
        </div>
        <div v-else class="w-full shadow italic rounded bg-white p-4">
            No hypervisor was found... Please go to the supervisor page...
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return { }
        },
        computed: {
            hosts() {
                return this.$store.getters['supervisor/hosts']
            },
            shouldShowEdit() {
                return this.$store.state.supervisor.shouldShowEdit
            }
        },
        async mounted() {
            this.$store.dispatch(Nova.E.FETCH_HYPERVISORS)

            await this.$store.dispatch(Nova.E.FETCH_HOSTS, this.$route, { root: true});
        }
    }
</script>
