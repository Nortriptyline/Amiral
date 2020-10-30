<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Club
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <create-club-form />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import CreateClubForm from './CreateClubForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        props: ['club'],

        components: {
            AppLayout,
            CreateClubForm,
            JetSectionBorder,
        },
    }
</script>
