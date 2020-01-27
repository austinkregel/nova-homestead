<template>
    <div>
        devices. Like usb drives, peripherals, PCIE devices...
        <pre>{{ host.devices }}</pre>
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
        }
    }
</script>
