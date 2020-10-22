<template>
    <app-layout>
        <template #header>
            <amiral-header>
                {{ $page.club.name }}
            </amiral-header>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <update-club-information-form :club="$page.club" />

                <jet-section-border />

                <!-- <update-password-form class="mt-10 sm:mt-0" />

                <div v-if="$page.jetstream.canManageTwoFactorAuthentication">
                    <jet-section-border />

                    <two-factor-authentication-form class="mt-10 sm:mt-0" />
                </div>

                <jet-section-border />

                <delete-user-form class="mt-10 sm:mt-0" /> -->
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import AmiralHeader from '@/Amiral/Header'
    import DeleteUserForm from './DeleteUserForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
    import UpdatePasswordForm from './UpdatePasswordForm'
    import UpdateClubInformationForm from './UpdateClubInformationForm'

    export default {
        props: ['sessions'],

        components: {
            AppLayout,
            AmiralHeader,
            DeleteUserForm,
            JetSectionBorder,
            TwoFactorAuthenticationForm,
            UpdatePasswordForm,
            UpdateClubInformationForm,
        },
    }
</script>
