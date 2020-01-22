<template>
    <div class="flex flex-wrap w-full">
        <div class="text-xl text-gray-700">Hypervisors</div>
        <div v-for="hypervisor in hypervisors" :key="hypervisor.id" class="w-full">
            <hypervisor :hypervisor="hypervisor"></hypervisor>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            hypervisors() {
                return this.$store.state.homestead.hypervisors;
            }
        },
        mounted() {
            this.$store.dispatch(Nova.E.FETCH_HYPERVISORS)
            this.$store.commit('homestead/hosts', [])
        }
    }
</script>
