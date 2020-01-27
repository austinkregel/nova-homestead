<template>
    <div>
        <div>
            <div>This will ask the guest operating system to reboot.</div>
            <button @click.prevent="rebootVm" class="px-4 py-2 border-blue-500 border text-blue-600 rounded">
                Restart Machine
            </button>
        </div>
        <div class="mt-8">
            <div>This will ask the guest operating system to shutdown.</div>
            <button @click.prevent="shutdown" class="px-4 py-2 border-red-500 border text-red-600 rounded">
                Shutdown
            </button>
        </div>
        <div class="mt-8">
            <div class="font-bold text-red-700">This is a potentially destructive action. This is like holding your power button, and then restarting the machine.</div>
            <button @click.prevent="hardPowerOff" class="px-4 py-2 bg-red-500 text-red-100 rounded">
                Hard power off
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        computed: {
            host() {
                return this.$store.getters['supervisor/host'];
            },
            hypervisor() {
                return this.$store.state.supervisor.hypervisor;
            }
        },
        methods: {
            async rebootVm() {
                await this.$store.dispatch(Nova.E.REBOOT, this.host, { root: true })
            },
            async shutdown() {
                await this.$store.dispatch(Nova.E.SHUTDOWN, this.host, { root: true })
            },
            async hardPowerOff() {
                await this.$store.dispatch(Nova.E.FORCE_STOP, this.host, { root: true })
            }
        }
    }
</script>
