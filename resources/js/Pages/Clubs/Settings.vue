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

        <create-club-role-form></create-club-role-form>

        <jet-section-border />
        
        <manage-club-role-form
          :roles="$page.club.roles"
        ></manage-club-role-form>
        <jet-section-border />
        <!-- <club-roles-form :availableRoles="$page.club.roles" :users="$page.users"></club-roles-form> -->
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
import AppLayout from "@/Layouts/AppLayout";
import AmiralHeader from "@/Amiral/Header";
import JetSectionBorder from "@/Jetstream/SectionBorder";
import UpdateClubInformationForm from "./UpdateClubInformationForm";
import CreateClubRoleForm from "./CreateClubRoleForm";
import ManageClubRoleForm from "./ManageClubRoleForm";
import ClubRolesForm from "./ClubRolesForm";

export default {
  props: ["sessions"],

  components: {
    AppLayout,
    AmiralHeader,
    JetSectionBorder,
    UpdateClubInformationForm,
    CreateClubRoleForm,
    ManageClubRoleForm,
    ClubRolesForm,
  },
};
</script>
