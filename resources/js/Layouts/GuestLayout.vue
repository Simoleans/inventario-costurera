<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col p-8 rounded-md sm:justify-center items-center pt-6 sm:pt-0 bg-white dark:bg-gray-900">
        <div>
            <Link href="/">
                <img src="/icons/logo.png" alt="Logo" class="w-[450px] h-auto" />
            </Link>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 border border-gray-300  bg-gray-100 dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
