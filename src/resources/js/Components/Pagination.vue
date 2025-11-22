<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    pagination: Object
})
</script>

<template>
    <nav class="relative flex justify-center my-5">
        <template v-for="link in pagination.links" :key="link.label">
            <Link
                preserve-scroll
                :href="link.url ?? ''"
                v-html="link.label"
                class="flex items-center justify-center px-3 py-2 font-medium text-sm rounded-lg text-gray-900"
                :class="{ 'bg-gray-300': link.active, '!text-gray-500': !link.url }"
            />
        </template>
    </nav>
</template>
