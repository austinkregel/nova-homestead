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
                return this.$store.getters['homestead/host'];
            },
            hypervisor() {
                return this.$store.state.homestead.hypervisor;
            }
        }
    }
</script>
