<template>
    <div>
        <div class="text-xl font-bold">This is a permanent and destructive action. This will delete all your VM files.</div>
        <div class="mt-4">
            <button @click.prevent="destroyVm" class="px-4 py-2 bg-red-500 text-red-100 rounded">
                Delete virtual machine
            </button>
        </div>
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
        },
        methods: {
            async destroyVm() {
                await this.$store.dispatch(Nova.E.DESTROY_VM, this.host)
                this.$router.push({
                    name: 'homestead.index',
                    params: {
                        homestead: this.homestead.id
                    }
                })
            }
        }
    }
</script>
