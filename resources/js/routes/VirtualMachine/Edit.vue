<template>
    <div>

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
</template>

<script>
    export default {
        props: [],
        methods: {
            async toggleVmStatus() {
                await this.$store.dispatch(Nova.E.TOGGLE_VM, {}, { root: true});
            },
        },
        computed: {
            shouldBeOn() {
                return this.$store.state.supervisor.shouldBeOn;
            },
            host() {
                return this.$store.state.supervisor.host;
            },
            hypervisor() {
                return this.$store.state.supervisor.hypervisor;
            }
        }
    }
</script>

<style scoped>

</style>
