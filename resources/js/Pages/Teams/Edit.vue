<template>
  <app-layout>
    <template #header> Team - {{ $page.team.name }} </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <update-team-information
          :team="$page.team"
        ></update-team-information>
      </div>
    </div>
  </app-layout>
</template>

<script>
import FormSection from "../../Jetstream/FormSection.vue";
import AppLayout from "../../Layouts/AppLayout.vue";
import UpdateTeamInformation from "./UpdateTeamInformation.vue";

export default {
  components: { FormSection, AppLayout, UpdateTeamInformation },
};
</script>

<style>
</style>