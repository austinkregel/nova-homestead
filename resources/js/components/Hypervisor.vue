<template>
    <div class="flex flex-wrap w-full">
        <div class="shadow bg-white rounded w-full mt-4">
            <div class="p-4 flex flex-col">
                <router-link :to="{ name: 'homestead.index', params: { hypervisor: hypervisor.id } }" class="text-xl text-blue-500 underline">{{ hypervisor.name }}</router-link>
                <div>
                    <span class="text-xs" v-if="hypervisor.hypervisor">{{ hypervisor.hypervisor }} -</span>
                    <span class="text-xs" v-if="hypervisor.ip">{{ hypervisor.ip }} -</span>
                    <span class="text-xs">{{ hypervisor.host_os }}</span>
                    <span class="text-xs">{{ hypervisor.connection }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['hypervisor']
    }
</script>

<style scoped>

</style>
